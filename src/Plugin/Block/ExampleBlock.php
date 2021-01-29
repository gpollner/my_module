<?php

namespace Drupal\my_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "my_module_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("My Module")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $settings = \Drupal::configFactory()->get('my_module.settings');
    $total_items = \Drupal::database()->select('node_field_data', 'n')
      ->fields('n', ['nid'])
      ->condition('n.type', $settings->get('bundle'))
      ->orderBy('n.created', 'DESC')
      ->execute()
      ->fetchCol();
    $total_count = count($total_items);

    $storage = \Drupal::entityManager()->getStorage('node');
    foreach ($total_items as $item) {
      $node = $storage->load($item);
      $titles[] = $node->label();
      if (count($titles) > $settings->get('count')) {
        break;
      }
    }
    $bundle = $settings->get('bundle');
    $markup = "<h3>There are $total_count nodes of type $bundle</h3><p>These are the most recent: <br/>";
    foreach ($titles as $title) {
      $markup .= "· $title<br />";
    }
    $markup .= "</p>";
    $build['content'] = [
      '#markup' => $markup,
    ];
    return $build;
  }

}
