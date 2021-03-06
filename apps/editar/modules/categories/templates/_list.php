<table cellspacing="0" class="tv_admin_list">
  <thead>
    <tr>
      <th width="1%">
        <input type="checkbox" onclick="window.click_checkbox_all('category', this.checked)">
      </th>
      <th colspan="3" width="3%"></th>
      <th width="1%">Id</th>
      <th>Cod - Nombre</th>
      <th>Obj. MM.</th>
    </tr>
  </thead>
  
  <tbody>
<?php if (count($categories) == 0):?>
    <tr>
      <td colspan="8">
        No existen categorias en la BBDD.
      </td>
    </tr>
<?php else:?>
       <?php foreach($categories as $c): $children = $c->getChildren(); $has_children = count($children); $level = 0 ?>

    <tr onmouseover="this.addClassName('tv_admin_row_over')" onmouseout="this.removeClassName('tv_admin_row_over')" 
        class="tv_admin_row_0 c_0" id="row_cat_<?php echo $c->getId()?>" data-level="<?php echo $level?>">
      <td>
        <input id="<?php echo $c->getId()?>" class="category_checkbox" type="checkbox">
      </td>
      <td>
      <?php include_partial('delete', array('has_children' => $has_children, 'c' => $c)) ?>
      </td>
      <td>
      <?php echo m_link_to(image_tag('admin/mbuttons/edit_inline.gif', 'alt=editar title=editar'), 
                           'categories/edit?id=' . $c->getId(), 
                           array('title' => 'Editar categoria ' . $c->getId()), 
                           array('width' => '800')) ?>
      </td>
<td>
      <?php echo m_link_to(image_tag('admin/mbuttons/copy_inline.gif', 'alt=nuevo title=Nueva categoría hijo'), 
                           'categories/create?parent_id=' . $c->getId(), 
                           array('title' => 'Nueva categoria hijo para ' . $c->getId()), 
                           array('width' => '800')) ?>
      </td>
<!--
Fuera del alcance de #4342.
<td>
    <?php echo m_link_to(image_tag('admin/mbuttons/relation_inline.gif', 'alt=relations title=relations'), 
                         'categories/showrelations?id=' . $c->getId(), 
                         array('title' => 'Editar relaciones de la categoria ' . $c->getId()), 
                         array('width' => '800')) ?>
      </td>
-->
<td>
      <?php echo $c->getId() ?> 
      </td>
<td>

<ul class="category_tree" >
   <li class="<?php echo (($has_children)?'collapsed noload':'element') ?>" >
   <span class="icon" onclick="javascript:toggle_section_cat(<?php echo $c->getId() ?>, this, <?php echo $level ?>)">&nbsp;</span> 
   <span id="info_cat_<?php echo $c->getId()?>"> <?php echo $c->getCodName() ?> </span>
   </li>
</ul>


    </td>
    <td>
      <?php echo $c->getNumMm() ?>
      </td>
    </tr>
  <?php endforeach?>
<?php endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="10">&nbsp;</th>
    </tr>
  </tfoot>
</table>



<?php if (isset($msg_alert)) echo m_msg_alert($msg_alert) ?>
