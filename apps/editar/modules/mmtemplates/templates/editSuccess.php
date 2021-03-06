<?php if( isset($mm_template) ): ?>

  <div style="text-align:right;">
    <a href="#" onclick="$('edit_serials').innerHTML=''; return false"><img src="/images/admin/buttons/close.png" alt="CERRAR" /></a>
  </div>

  
  <!-- actualizar vistaPrevia -->
  <ul id="menuTab">
    <li id="metaMmTemplate"   class="noSelMmTemplate" >
      <a href="#" onclick="menuTabTemplate.select('metaMmTemplate'); return false;" ><?php echo __('Metadatos')?></a>
    </li>
    <li id="categoryMmTemplate" class="noSelMmTemplate" >
      <a href="#" onclick="menuTabTemplate.select('categoryMmTemplate'); return false;" ><?php echo __('Categorias')?></a>
    </li>
    <li id="personMmTemplate" class="noSelMmTemplate" >
      <a href="#" onclick="menuTabTemplate.select('personMmTemplate'); return false;" ><?php echo __('Personas')?></a>
    </li>
  </ul>
  
  <div class="background_id">
    <?php echo __('V. Defecto para la serie')?> <?php echo $mm_template->getSerialId() ?>
  </div>
  
  
  <div id="metaMmTemplateDiv"  style="display:none;">
    <?php include_partial('edit_meta', array('mm' => $mm_template, 'langs' => $langs)) ?>
  </div>
  
  <div id="categoryMmTemplateDiv" class="virtual_edit"  style="display:none;">
    <?php include_partial('edit_category', array('mm' => $mm_template, 'langs' => $langs)) ?>
  </div>

  <div id="personMmTemplateDiv" style="display:none;">
    <?php include_partial('edit_person', array('mm' => $mm_template, 'langs' => $langs, 'roles' => $roles)) ?>
  </div>
 
  <?php echo javascript_tag("
    window.menuTabTemplate = new menuTab2Class(document.location.hash);
  ") ?>
    
<?php endif?>

