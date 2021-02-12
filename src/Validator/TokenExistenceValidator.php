<?php

namespace App\Validator;

use App\Dto\ActivationLinkParams;
use App\Entity\ActivationLink;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Validator
 */
class TokenExistenceValidator extends ConstraintValidator
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
     * @param $value
     * @param Constraint $constraint
     *
     * @throws Exception
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var ActivationLinkParams $value */
        if (!$constraint instanceof TokenExistence) {
            throw new UnexpectedTypeException($constraint, TokenExistence::class);
        } elseif (!$value instanceof ActivationLinkParams) {
            throw new UnexpectedTypeException($value, ActivationLinkParams::class);
        }

        if (null === $value->getToken()) {
            return;
        }

        $repository = $this->em->getRepository(ActivationLink::class);
        /** @var ActivationLink $link */
        $link = $repository->findOneBy(['token' => $value->getToken()]);

        if (null === $link) {
            $this->context
                ->buildViolation("Token {{ token }} doesn't exist.")
                ->setParameter('{{ token }}', $value->getToken())
                ->setCode(TokenExistence::TOKEN_DOES_NOT_EXIST)
                ->atPath('token')
                ->addViolation();

            return;
        }

        if ($link->getExpireAt() < new DateTimeImmutable('now', new DateTimeZone('UTC'))) {
            $this->context
                ->buildViolation("Activation link has expired.")
                ->setCode(TokenExistence::TOKEN_EXPIRED)
                ->atPath('token')
                ->addViolation();

            return;
        }
    }
}
