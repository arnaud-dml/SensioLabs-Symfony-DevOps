<?php

namespace App\Tests\Common;

use App\Entity\Gardener;
use App\Entity\Token;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

trait HandlerExpectsTrait
{
    public function formExpectsHandleRequest(): void
    {
        $this->form
            ->expects(self::once())
            ->method('handleRequest')
            ->with(self::isInstanceOf(Request::class));
    }

    public function formExpectsIsSubmitted(): void
    {
        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(true);
    }

    public function formExpectsIsValid(): void
    {
        $this->form
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(true);
    }

    public function formExpectsIsNotValid(): void
    {
        $this->form
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(false);
    }

    public function formExpectsAddError(): void
    {
        $this->form
            ->expects(self::once())
            ->method('addError')
            ->with(self::isInstanceOf(FormError::class));
    }

    /**
     * @param string $expiredAt
     */
    public function tokenExpectsFindOneBy(string $expiredAt = 'now +1 day'): void
    {
        $this->tokenRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->willReturn(
                (new Token())
                    ->setType('REGISTER')
                    ->setExpiredAt(new \DateTime($expiredAt))
                    ->setGardener(
                        (new Gardener())
                            ->setUsername('johndoe')
                            ->setEmail('john-doe@gmail.com')
                    )
            );
    }
}
