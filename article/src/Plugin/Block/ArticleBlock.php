<?php

namespace Drupal\article\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Provides a 'article' block.
 *
 * @Block(
 *   id = "article_block",
 *   admin_label = @Translation("Article block"),
 *   category = @Translation("Custom article block example")
 * )
 */
class ArticleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */

  /**
   * Public function build() {
   * return array(
   * '#type' => 'markup',
   * '#markup' => 'This block list the article.',
   * );
   * } .
   */
  public function article_theme() {
    return [
      'article' => [
        'variables' => ['title' => NULL, 'description' => NULL],
        'template' => 'block--article',
      ],
    ];
  }

  /**
   *
   */
  public function build() {
    $build = [];

    $build['heading']['#markup'] =  $this->configuration['heading'];
    $build['sub_heading']['#markup'] =  $this->configuration['sub_heading'] ;

    $image = $this->configuration['image'];
    if (!empty($image[0])) {
      if ($file = File::load($image[0])) {
        $build['image'] = [
          '#theme' => 'image_style',
          '#style_name' => 'large',
          '#attributes' => [
          'style' => ['width: 100%;'],
          ],
          '#uri' => $file->getFileUri(),
        ];
      }
    }

    $build['body']['#markup'] = '<div>' . $this->configuration['body']['value'] . '</div>';
    return [
      '#theme' => 'article',
      '#data' => $build,
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    return $build;
  }

  /**
   *
   */
  public function blockForm($form, FormStateInterface $formState) {
    $form['heading'] = [
      '#type' => 'textfield',
      '#title' => t('Heading'),
      '#description' => t('Enter the main heading'),
      '#default_value' => 'Main heading',
    ];

    $form['sub_heading'] = [
      '#type' => 'textfield',
      '#title' => t('Sub heading'),
      '#description' => t('Enter the sub heading'),
      '#default_value' => 'Sub heading',
    ];

    $form['body'] = [
      '#type' => 'text_format',
      '#title' => t('Body'),
      '#description' => t('Main body'),
      '#format' => 'full_html',
      '#rows' => 50,
      '#default_value' => '',
    ];

    $form['image'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://upload/hello',
      '#title' => t('Image'),
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg', 'jpeg', 'png', 'gif'],
      ],
      '#default_value' => isset($this->configuration['image']) ? $this->configuration['image'] : '',
      '#description' => t('The image to display'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   *
   */
  public function blockSubmit($form, FormStateInterface $formState) {
    // Save image as permanent.
    $image = $formState->getValue('image');
    if ($image != $this->configuration['image']) {
      if (!empty($image[0])) {
        $file = File::load($image[0]);
        $file->setPermanent();
        $file->save();
      }
    }

    // Save configurations.
    $this->configuration['heading'] = $formState->getValue('heading');
    $this->configuration['sub_heading'] = $formState->getValue('sub_heading');
    $this->configuration['body'] = $formState->getValue('body');
    $this->configuration['image'] = $formState->getValue('image');
  }

}
