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
        $user->setStreet($signUpStep2['street'] ?? '');
        $user->setNumber($signUpStep2['number'] ?? '');
        $user->setZipCode($signUpStep2['zipCode'] ?? '');
        $user->setCity($signUpStep2['city'] ?? '');
        $user->setState($signUpStep2['state'] ?? '');

        $user->setPhone($signUpStep3['phone'] ?? '');
        $user->setMobile($signUpStep3['mobile'] ?? '');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
