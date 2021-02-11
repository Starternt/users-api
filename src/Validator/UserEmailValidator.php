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
class UserEmailValidator extends ConstraintValidator
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
        if (!$constraint instanceof UserEmail) {
            throw new UnexpectedTypeException($constraint, UserEmail::class);
        } elseif (!$value instanceof UserDto) {
            throw new UnexpectedTypeException($value, UserDto::class);
        }

        if (null === $value->getEmail()) {
            return;
        }

        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('count(u)')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameters(
                [
                    'email' => $value->getEmail(),
                ]
            );

        $count = (int)$qb->getQuery()->getSingleScalarResult();
        if ($count > 0) {
            $this->context
                ->buildViolation("User with email {{ email }} already registered.")
                ->setParameter('{{ email }}', $value->getLogin())
                ->setCode(UserEmail::EMAIL_NOT_UNIQUE)
                ->atPath('email')
                ->addViolation();

            return;
        }
    }
}
