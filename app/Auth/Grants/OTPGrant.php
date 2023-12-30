<?php

namespace App\Auth\Grants;

use RuntimeException;
use Illuminate\Http\Request;
use App\Exceptions\OtpException;
use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\RequestEvent;
use App\Auth\Grants\OtpVerifierFactory;
use App\Auth\OTPVerifier;
use App\Http\Responses\ErrorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OtpGrant extends AbstractGrant
{
    /**
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
     */
    public function __construct(
        RefreshTokenRepositoryInterface $refreshTokenRepository
    ) {
        $this->setRefreshTokenRepository($refreshTokenRepository);

        $this->refreshTokenTTL = new \DateInterval('P1M');
    }

    /**
     * {@inheritdoc}
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    ) {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $scopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $scopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $otp = $this->getRequestParameter('otp', $request);
        if (is_null($otp)) {
            throw OAuthServerException::invalidRequest('otp');
        }

        $email = $this->getRequestParameter('email', $request);
        if (is_null($email)) {
            throw OAuthServerException::invalidRequest('email');
        }

        $otpVerifierParam = $this->getRequestParameter('otp_verifier', $request);

        // dd($this->getRequestParameter('otp_verifier', $request, 'BL_INTERNAL'));
        $otpVerifier = new OTPVerifier();
        // $otpVerifier = OtpVerifierFactory::getOtpVerifier(
        //     $this->getRequestParameter('otp_verifier', $request, 'BL_INTERNAL')
        // );

        // if (is_null($otpVerifier)) {
        //     throw OtpException::invalidOtpVerifier();
        // }

        $isValidOtp = $otpVerifier->verify($email, $otp);

        if (!$isValidOtp) {
            throw new OtpException("کد یک بار مصرف وارد شده اشتباه است");
            // throw OtpException::invalidOtp();
            // return response(
            //     ['message' => 'کد یک بار مصرف وارد شده اشتباه است']
            // );
        }



        $user = $this->getUserEntityByUserOtp(
            $email,
            $this->getIdentifier(),
            $client
        );

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    private function getUserEntityByUserOtp($email, $grantType, ClientEntityInterface $clientEntity)
    {
        $provider = config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.' . $provider . '.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        $user = (new $model)->where('email', $email)->first();

        if (is_null($user)) {
            return;
        }

        return new User($user->getAuthIdentifier());
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'otp_grant';
    }
}
