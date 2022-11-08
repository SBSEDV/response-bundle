<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use SBSEDV\Bundle\FormBundle\SBSEDVFormBundle;
use Symfony\Component\Form\FormInterface;

/**
 * An Exception that will serialize an invalid form into an http response.
 */
class InvalidFormException extends \Exception
{
    public function __construct(
        private FormInterface $form,
        ?\Throwable $previous = null
    ) {
        if (!\class_exists(SBSEDVFormBundle::class)) {
            throw new \LogicException(\sprintf('Can not use %s when the sbsedv/form-bundle is not installed.', self::class));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            throw new \LogicException(\sprintf('The form is valid. Did you forget to check if the form is valid before throwing the %s exception?', self::class));
        }

        parent::__construct(previous: $previous);
    }

    /**
     * The invalid form.
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
