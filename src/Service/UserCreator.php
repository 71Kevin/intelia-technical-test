<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreator
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUserFromSessionData(SessionInterface $session): User
    {
        $signUpStep1 = $session->get('signUpStep1', []);
        $signUpStep2 = $session->get('signUpStep2', []);
        $signUpStep3 = $session->get('signUpStep3', []);
    
        $user = new User();
        $user->setFullname($signUpStep1['fullname'] ?? null);
        $user->setBirthdate(new \DateTime($signUpStep1['birthdate'] ?? 'now'));
        $user->setAddress($signUpStep2['address'] ?? null);
        $user->setPhone($signUpStep3['phone'] ?? null);
        $user->setMobile($signUpStep3['mobile'] ?? null);
    
        // Definir username e password com strings vazias
        $user->setUsername(strtolower(str_replace(' ', '.', $signUpStep1['fullname'])));
        $user->setPassword('default_password');  // Pode ser alterado mais tarde pelo usuário
        
    
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    
        return $user;
    }
    
}
