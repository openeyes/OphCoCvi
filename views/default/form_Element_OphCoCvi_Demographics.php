
<div class="element-fields row">
    <div class="large-6 column">
        <?php echo $form->textField($element, 'title_surname', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->textField($element, 'other_names', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->textField($element, 'address', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->textField($element, 'postcode', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->textField($element, 'email', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->textField($element, 'telephone', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->datePicker($element, 'date_of_birth', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->dropDownList($element, 'gender_id', CHtml::listData(Gender::model()->findAll(), 'id', 'name'), array('empty' => '- Please Select -'), false, array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->dropDownList($element, 'ethnic_group_id', CHtml::listData(EthnicGroup::model()->findAll(), 'id', 'name'), array('empty' => '- Please Select -'), false, array('label' => 4, 'field' => 8)) ?>
    </div>
    <div class="large-6 column">
        <?php echo $form->textField($element, 'nhs_number', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->textField($element, 'gp_name', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->textField($element, 'gp_address', array(), array(), array('label' => 4, 'field' => 8)) ?>
        <?php echo $form->textField($element, 'gp_telephone', array(), array(), array('label' => 4, 'field' => 8)) ?>
    </div>
</div>
