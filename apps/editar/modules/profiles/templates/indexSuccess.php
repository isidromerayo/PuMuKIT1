<h3 class="cab_body_div"><img src="/images/admin/cab/config_ico.png"/> <?php echo __('Perfiles')?></h3>

<div id="tv_admin_container">
  <div id="tv_admin_bar">
    <div id="preview_profile" style="padding:5px; border: solid 1px #DDD; background: #8eb0bc url(/images/admin/cab/cab.gif) repeat-x scroll 0% 0%">
      <?php include_component('profiles', 'preview') ?>
    </div>
  </div>

  <div id="tv_admin_content" >
    <div id="list_profiles" name="list_profiles" act="/profiles/list">
      <?php include_component('profiles', 'list') ?>
    </div>

    <div style="float:right; width:50%">
      <ul class="tv_admin_actions">
        <!-- Falta -->
        <li><?php echo button_to_function('nuevo', 'Modalbox.show("profiles/create", {title:"' . __('Editar Nuevo Rol') . '", width:800}); return false;', array ('class' => 'tv_admin_action_create')) ?></li>
      </ul>
    </div>


    <select id="options_profiles" style="margin: 10px 0px; width: 33%" title="<?php echo __('Acciones sobre elementos seleccionados')?>" onchange="window.change_select('profile', $('options_profiles'))">
      <option value="default" selected="selected"><?php echo __('Selecciona una acci&oacute;n...')?></option>
      <option disabled="">---</option>
      <option value="delete_sel"><?php echo __('Borrar seleccionados')?></option>
      <option value="create"><?php echo __('Crear nuevo')?></option>
    </select>

  </div>    
</div>

