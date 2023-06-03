<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\FormAlterHandlers;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;

class EntityFormAlterHandler implements FormAlterHandlerInterface {
  protected AccountProxyInterface $account;

  public function __construct(AccountProxyInterface $account) {
    $this->account = $account;
  }

  public function alter(array &$entityForm, FormStateInterface $formState, string $formId): void {
    $this->addEntityFormClass($entityForm);
    $this->restrictRevisionAccess($entityForm);
  }

  protected function addEntityFormClass(array &$entityForm): void {
    $entityForm['#attributes']['class'][] = 'entity-form';
  }

  protected function restrictRevisionAccess(array &$entityForm): void {
    $hasPermission = $this->account->hasPermission('tengstrom_general__administer_revisions');

    if (!empty($entityForm['revision'])) {
      $entityForm['revision']['#access'] = $hasPermission;
    }
    if (!empty($entityForm['revision_information'])) {
      $entityForm['revision_information']['#access'] = $hasPermission;
    }
  }

}
