<?php

namespace App\Factory;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
    }

    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email,
            'password' => 'password',
            'firstname' => self::faker()->firstName,
            'lastname' => self::faker()->lastName,
            'roles' => [],
            'trigram' => 'ABC',
            'jobTitle' => UserJobTitleFactory::createOne()
        ];
    }

    public function withRoles(array $roles): self
    {
        return $this->with(['roles' => $roles]);
    }

    public function asAdmin():self
    {
        return $this->withRoles(['ROLE_ADMIN']);
    }


    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(User $user): void {
                $user->setPassword($this->userPasswordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                ));

                $user->setTrigram(UserService::generateTrigram($user));
            });
    }
}
