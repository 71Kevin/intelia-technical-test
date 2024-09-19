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
    private $signUpValidator;
    private $userCreator;
    private $session;

    public function __construct(
        SignUpValidator $signUpValidator,
        UserCreator $userCreator,
        SessionInterface $session
    ) {
        $this->signUpValidator = $signUpValidator;
        $this->userCreator = $userCreator;
        $this->session = $session;
    }

    public function signUp(Request $request): Response
    {
        return $this->render('profile/sign-up.html.twig');
    }

    public function handleStep1(Request $request): JsonResponse
    {
        $signUpStep1Request = new SignUpStep1Request($request);
        
        error_log('handleStep1 called with: ' . json_encode($signUpStep1Request));
    
        try {
            $this->session->set('signUpStep1', [
                'fullname' => $signUpStep1Request->getFullname(),
                'birthdate' => $signUpStep1Request->getBirthdate()
            ]);
    
            return new JsonResponse(['status' => Response::HTTP_OK]);
        } catch (\Exception $e) {
            error_log('Error in handleStep1: ' . $e->getMessage());
            return new JsonResponse(['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => $e->getMessage()]);
        }
    }     

    public function handleStep2(Request $request): JsonResponse
    {
        $signUpStep2Request = new SignUpStep2Request($request);
        
        try {
            $this->session->set('signUpStep2', [
                'address' => $signUpStep2Request->getAddress()
            ]);
    
            return new JsonResponse(['status' => Response::HTTP_OK]);
        } catch (\Exception $e) {
            error_log('Error in handleStep2: ' . $e->getMessage());
            return new JsonResponse(['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => $e->getMessage()]);
        }
    }    

    public function handleStep3(Request $request): JsonResponse
    {
        $signUpStep3Request = new SignUpStep3Request($request);
    
        try {
            $this->session->set('signUpStep3', [
                'phone' => $signUpStep3Request->getPhone(),
                'mobile' => $signUpStep3Request->getMobile()
            ]);
    
            $user = $this->userCreator->createUserFromSessionData($this->session);
    
            return new JsonResponse([
                'status' => Response::HTTP_OK,
                'entity' => $user->getId()
            ]);
        } catch (\Exception $e) {
            error_log('Error in handleStep3: ' . $e->getMessage());
            return new JsonResponse(['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => $e->getMessage()]);
        }
    }    
}
