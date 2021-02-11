<?php

namespace App\Validator;

use App\Dto\UserDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Validator
 */
class UserLoginValidator extends ConstraintValidator
{
    /**
     * Connection to database
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var UserDto $value */
        if (!$constraint instanceof UserLogin) {
            throw new UnexpectedTypeException($constraint, UserLogin::class);
        } elseif (!$value instanceof UserDto) {
            throw new UnexpectedTypeException($value, UserDto::class);
        }

        if (null === $value->getLogin()) {
            return;
        }

        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('count(u)')
            ->from(User::class, 'u')
            ->where('u.login = :login')
            ->setParameters(
                [
                    'login' => $value->getLogin(),
                ]
            );

        $count = (int)$qb->getQuery()->getSingleScalarResult();
        if ($count > 0) {
            $this->context
                ->buildViolation("User with login {{ login }} already exists.")
                ->setParameter('{{ login }}', $value->getLogin())
                ->setCode(UserLogin::LOGIN_NOT_UNIQUE)
                ->atPath('login')
                ->addViolation();

            return;
        }
    }
}
