<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\FormAlterHandlers;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Ordermind\DrupalTengstromShared\Helpers\EntityFormHelpers;
use Ordermind\DrupalTengstromShared\HookHandlers\FormAlterHandlerInterface;

class EntityDeleteFormAlterHandler implements FormAlterHandlerInterface {
  protected MessengerInterface $messenger;
  protected TranslationInterface $translator;

  public function __construct(MessengerInterface $messenger, TranslationInterface $translator) {
    $this->messenger = $messenger;
    $this->translator = $translator;
  }

  public function alter(array &$entityForm, FormStateInterface $formState, string $formId): void {
    $this->changeTitle($entityForm, $formState);
    $this->changeSuccessMessage($entityForm);
  }

  protected function changeSuccessMessage(array &$entityForm): void {
    $entityForm['actions']['submit']['#submit'][] = [
      $this,
      'formSubmitCallbackChangeSuccessMessage',
    ];
  }

  public function formSubmitCallbackChangeSuccessMessage(array $form, FormStateInterface $formState): void {
    $objEntityForm = EntityFormHelpers::getEntityForm($formState);
    $entity = $objEntityForm->getEntity();

    $this->messenger->deleteAll();
    $this->messenger->addMessage($this->translator->translate('%label has been deleted.', ['%label' => $entity->label()]));
  }

  protected function changeTitle(array &$entityForm, FormStateInterface $formState): void {
    $objEntityForm = EntityFormHelpers::getEntityForm($formState);
    $entity = $objEntityForm->getEntity();

    $entityForm['#title'] = $this->translator->translate('Are you sure you want to delete %label?', ['%label' => $entity->label()]);
  }

}
