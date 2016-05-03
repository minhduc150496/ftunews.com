<?php
if ( !defined('ABSPATH') )
    exit; // Shhh
?>
<hr>

<p>
    <label>
        <input type="checkbox" data-toggler="only-logged-users" name="tp_options[limitations][logged_users_only_vote]" value="1" <?php checked(true, isset($options->limitations->logged_users_only_vote)); ?>> <?php _e('Only logged in users can vote', 'tp-ul-addon') ?>
    </label>
</p>

<p data-toggle="only-logged-users" class="<?php echo isset($options->limitations->logged_users_only_vote)
            ? '' : 'hide'; ?>"><?php _e('With these roles', 'tp-ul-addon'); ?></p>

<p data-toggle="only-logged-users" class="<?php echo isset($options->limitations->logged_users_only_vote)
            ? '' : 'hide'; ?>">
    <?php
    $editable_roles = array_reverse(get_editable_roles());
    foreach ( $editable_roles as $role => $details ):
        $name = translate_user_role($details['name']);
        ?>
        <label>
            <input type="checkbox" name="tp_options[limitations][logged_user_role][<?php echo esc_attr($role); ?>]" value="1" <?php checked(true, isset($options->limitations->logged_user_role->{$role})); ?>> <?php echo $name; ?>
        </label>
        &nbsp;&nbsp;&nbsp;
        <?php
    endforeach;
    ?>
</p>