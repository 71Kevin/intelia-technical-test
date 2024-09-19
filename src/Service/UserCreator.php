<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreator
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUserFromSessionData(SessionInterface $session): User
    {
        $signUpStep1 = $session->get('signUpStep1', []);
        $signUpStep2 = $session->get('signUpStep2', []);
        $signUpStep3 = $session->get('signUpStep3', []);

        $user = new User();
        $user->setFullname($signUpStep1['fullname'] ?? '');
        $user->setBirthdate(new \DateTime($signUpStep1['birthdate'] ?? 'now'));
        $user->setAddress($signUpStep2['address'] ?? '');
        $user->setPhone($signUpStep3['phone'] ?? '');
        $user->setMobile($signUpStep3['mobile'] ?? '');

        $user->setUsername(strtolower(str_replace(' ', '.', $signUpStep1['fullname'] ?? '')));
        
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'default_password');
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
