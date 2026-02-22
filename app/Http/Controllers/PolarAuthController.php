<?php

namespace App\Http\Controllers;

use App\Support\API\Polar\Auth\PolarAuthException;
use App\Support\API\Polar\Auth\PolarAuthService;
use App\Support\API\Polar\Auth\PolarTokenManager;
use App\Support\API\Polar\PolarApiException;
use App\Support\API\Polar\Resources\PolarUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PolarAuthController extends Controller
{
    protected const GENERIC_ERROR_MESSAGE = 'Something went wrong when trying to authorize access to your account. Please try again in a couple of minutes.';

    public function redirect(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $state = encrypt([
            'user_id' => $user->id,
        ]);

        session(['polar_oauth_state' => $state]);

        $redirectUrl = PolarAuthService::getAuthorizationUrl($state);

        return redirect($redirectUrl);
    }

    public function callback(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Handle errors.
        if ($request->has('error')) {
            $error_message = match ($request->error) {
                'access_denied' => 'Access has been denied to this account.',
                'temporarily_unavailable' => self::GENERIC_ERROR_MESSAGE,
                'server_error' => self::GENERIC_ERROR_MESSAGE,
                default => self::GENERIC_ERROR_MESSAGE,
            };

            return redirect()->route('account.settings')
                ->with('error', $error_message);
        }

        // Checking for missing or invalid state.
        $state = $request->state;
        if (! $state || $state !== $request->session()->get('polar_oauth_state')) {
            return redirect()->route('account.settings')
                ->with('error', self::GENERIC_ERROR_MESSAGE);
        }

        // Handling wrong users.
        $decryptedState = decrypt($state);
        if (! $decryptedState['user_id'] || $decryptedState['user_id'] !== Auth::user()->id) {
            return redirect()->route('account.settings')
                ->with('error', self::GENERIC_ERROR_MESSAGE);
        }

        $request->session()->forget('polar_oauth_state');

        try {
            // Get the OAuth tokens.
            $tokens = PolarAuthService::exchangeToken($request['code']);

            // Registher the user to be able to access its data.
            PolarUserResource::register($tokens->xUserId, $tokens->accessToken);

            // Store the tokens.
            $polarProfile = PolarTokenManager::store($user, $tokens);

            // Make sure that we have all the required data.
            $userInfo = PolarUserResource::get($tokens->xUserId, $tokens->accessToken);
            $polarProfile->update([
                'first_name' => $userInfo['first-name'],
                'last_name' => $userInfo['last-name'],
            ]);

            // At this point the registration should be successful.
            return redirect()->route('account.settings')
                ->with('success', 'Your Polar account has been linked.');
        } catch (PolarApiException $e) {
            Log::error('Polar API error', ['message' => $e->getMessage()]);

            return redirect()->route('account.settings')
                ->with('error', self::GENERIC_ERROR_MESSAGE);
        } catch (PolarAuthException $e) {
            Log::error('Polar auth error', ['message' => $e->getMessage()]);

            return redirect()->route('account.settings')
                ->with('error', self::GENERIC_ERROR_MESSAGE);
        } catch (\Throwable $th) {
            Log::error('Unexpected error', ['message' => $th->getMessage()]);

            return redirect()->route('account.settings')
                ->with('error', self::GENERIC_ERROR_MESSAGE);
        }
    }
}
