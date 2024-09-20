<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Request\SignUpStep1Request;
use App\Http\Request\SignUpStep2Request;
use App\Http\Request\SignUpStep3Request;
use App\Service\SignUpValidator;
use App\Service\UserCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserController extends AbstractController
{
    private SignUpValidator $signUpValidator;
    private UserCreator $userCreator;
    private SessionInterface $session;

    public function __construct(
        SignUpValidator $signUpValidator,
        UserCreator $userCreator,
        SessionInterface $session
    ) {
        $this->signUpValidator = $signUpValidator;
        $this->userCreator = $userCreator;
        $this->session = $session;
    }

    public function signUp(): Response
    {
        return $this->render('profile/sign-up.html.twig');
    }

    private function handleStep(Request $request, string $stepKey, array $sessionData): JsonResponse
    {
        try {
            $this->session->set($stepKey, $sessionData);
    
            $user = $this->userCreator->createOrUpdateUserFromSessionData($this->session);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
    
            return new JsonResponse(['status' => Response::HTTP_OK]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function fetchSavedData(): JsonResponse
    {
        try {
            $signUpStep1 = $this->session->get('signUpStep1', []);
            $signUpStep2 = $this->session->get('signUpStep2', []);
            $signUpStep3 = $this->session->get('signUpStep3', []);

            $sessionData = array_merge($signUpStep1, $signUpStep2, $signUpStep3);

            return new JsonResponse($sessionData, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function handleStep1(Request $request): JsonResponse
    {
        $signUpStep1Request = new SignUpStep1Request($request);
        $sessionData = [
            'fullname' => $signUpStep1Request->getFullname(),
            'birthdate' => $signUpStep1Request->getBirthdate(),
        ];

        return $this->handleStep($request, 'signUpStep1', $sessionData);
    }

    public function handleStep2(Request $request): JsonResponse
    {
        $signUpStep2Request = new SignUpStep2Request($request);
        $sessionData = [
            'street' => $signUpStep2Request->getStreet(),
            'number' => $signUpStep2Request->getNumber(),
            'zipCode' => $signUpStep2Request->getZipCode(),
            'city' => $signUpStep2Request->getCity(),
            'state' => $signUpStep2Request->getState()
        ];
    
        return $this->handleStep($request, 'signUpStep2', $sessionData);
    }    

    public function handleStep3(Request $request): JsonResponse
    {
        $signUpStep3Request = new SignUpStep3Request($request);
        $sessionData = [
            'phone' => $signUpStep3Request->getPhone(),
            'mobile' => $signUpStep3Request->getMobile(),
        ];
    
        $response = $this->handleStep($request, 'signUpStep3', $sessionData);
    
        if ($response->getStatusCode() === Response::HTTP_OK) {
            try {
                $user = $this->userCreator->createOrUpdateUserFromSessionData($this->session);
    
                return new JsonResponse([
                    'status' => Response::HTTP_OK,
                    'entity' => $user->getId(),
                ]);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    
        return $response;
    }

    public function clearSession(): JsonResponse
    {
        try {
            $this->session->clear();
            return new JsonResponse(['status' => Response::HTTP_OK, 'message' => 'Session cleared']);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }
}
