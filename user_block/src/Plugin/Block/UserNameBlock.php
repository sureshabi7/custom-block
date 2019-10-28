<?php

namespace Drupal\user_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'User Name' Block.
 *
 * @Block(
 *   id = "my_show_user",
 *   admin_label = @Translation("Show User"),
 *   category = @Translation("Show User"),
 * )
 */

class UserNameBlock extends BlockBase {

    /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#markup' => '<h2>Welcome</h2>' . $this->t(\Drupal::currentUser()->getUsername()),
    );
    
  }

}