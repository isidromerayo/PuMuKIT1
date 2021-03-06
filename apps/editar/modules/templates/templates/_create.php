<div id="new_template">
  <?php echo form_tag('templates/create', 'class=float-left id=sf_asset_mkdir_form name=sf_asset_mkdir_form') ?>
    <fieldset>
      <div class="form-row">
        <?php echo label_for('dir', __('Crear un template:'), '') ?>
        <div class="content">
          <?php echo input_tag('name', null, 'size=15 id=dir') ?>
        </div>
      </div>
    </fieldset>

    <ul class="sf_asset_actions">
      <li><?php echo submit_tag(__('Create', array(), 'navigator'), array (
        'name'    => 'create',
        'class'   => 'sf_asset_action_add_folder',
        'onclick' => "if($('dir').value.trim()=='') { alert('".__('Please enter a template name first')."');return false; }",
      )) ?></li>
    </ul>
  </form>
</div>