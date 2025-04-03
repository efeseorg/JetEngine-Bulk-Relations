<?php
/**
 * Plugin Name: Relaciones Masivas de JetEngine
 * Description: Un plugin para insertar relaciones masivas en las tablas JetEngine desde el backend de WordPress, incluyendo acciones masivas en CCT.
 * Version: 1.2
 * Author: Francisco Sánchez
 */
// Agregar una página de menú al backend de WordPress
add_action('admin_menu', 'rmj_add_admin_menu');

function rmj_add_admin_menu() {
    add_menu_page(
        __('Relaciones Masivas JetEngine', 'textdomain'), // Título de la página
        __('Relaciones Masivas', 'textdomain'),           // Nombre del menú
        'manage_options',                                     // Capacidad
        'relaciones-masivas-jetengine',                         // Slug del menú
        'rmj_admin_page',                                     // Función que dibuja la página
        'dashicons-admin-tools',                               // Icono del menú
        6                                                     // Posición en el menú
    );
}

// Mostrar el formulario en el backend de WordPress
function rmj_admin_page() {
    // Verificar que el usuario tenga los permisos necesarios
    if (!current_user_can('manage_options')) {
        wp_die(__('No tienes permisos suficientes para acceder a esta página.', 'textdomain'));
    }

    // Procesar el formulario si se ha enviado
    if (isset($_POST['rmj_submit'])) {
        // Verificar el nonce para proteger contra CSRF
        if (!isset($_POST['rmj_nonce']) || !wp_verify_nonce($_POST['rmj_nonce'], 'rmj_insert_nonce')) {
            wp_die(__('No autorizado', 'textdomain'));
        }
        rmj_insertar_relaciones_masivas();
    }

    // Obtener todas las tablas que empiecen con jet_rel_
    global $wpdb;
    $tables = $wpdb->get_col("SHOW TABLES LIKE '{$wpdb->prefix}jet_rel_%'");

    // Verificar si hay IDs seleccionados desde una acción masiva
    $selected_ids = get_option('rmj_selected_cct_items', []);

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Inserción Masiva de Relaciones JetEngine', 'textdomain'); ?></h1>

        <div id="rmj-instructions" style="background-color: #f9f9f9; border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
            <h3><?php esc_html_e('Cómo usar la Inserción Masiva de Relaciones', 'textdomain'); ?> / <?php esc_html_e('How to Use Bulk Relation Insertion', 'textdomain'); ?></h3>
            <?php if (empty($selected_ids)): ?>
                <ol>
                    <li><?php esc_html_e('Selecciona la tabla de relaciones de JetEngine en la que deseas insertar las conexiones.', 'textdomain'); ?> / <?php esc_html_e('Select the JetEngine relations table where you want to insert the connections.', 'textdomain'); ?></li>
                    <li><?php esc_html_e('Introduce el ID de la relación de JetEngine.', 'textdomain'); ?> / <?php esc_html_e('Enter the JetEngine relation ID.', 'textdomain'); ?></li>
                    <li><?php esc_html_e('Introduce el ID del objeto padre.', 'textdomain'); ?> / <?php esc_html_e('Enter the parent object ID.', 'textdomain'); ?></li>
                    <li><?php esc_html_e('Especifica el rango de IDs de los objetos hijo (Desde - Hasta) que deseas relacionar con el objeto padre.', 'textdomain'); ?> / <?php esc_html_e('Specify the range of child object IDs (From - To) that you want to relate to the parent object.', 'textdomain'); ?></li>
                    <li><?php esc_html_e('Haz clic en el botón "Insertar Relaciones".', 'textdomain'); ?> / <?php esc_html_e('Click the "Insert Relations" button.', 'textdomain'); ?></li>
                </ol>
            <?php else: ?>
                <p><?php esc_html_e('Has llegado a esta página mediante una acción masiva. Sigue estos pasos:', 'textdomain'); ?> / <?php esc_html_e('You have arrived at this page via a bulk action. Follow these steps:', 'textdomain'); ?></p>
                <ol>
                    <li><?php esc_html_e('Selecciona la tabla de relaciones de JetEngine.', 'textdomain'); ?> / <?php esc_html_e('Select the JetEngine relations table.', 'textdomain'); ?></li>
                    <li><?php esc_html_e('Introduce el ID de la relación de JetEngine.', 'textdomain'); ?> / <?php esc_html_e('Enter the JetEngine relation ID.', 'textdomain'); ?></li>
                    <li><?php esc_html_e('Introduce el ID del objeto padre.', 'textdomain'); ?> / <?php esc_html_e('Enter the parent object ID.', 'textdomain'); ?></li>
                    <li><?php printf(esc_html__('Los IDs de los objetos hijo seleccionados (%s) se utilizarán para crear las relaciones.', 'textdomain'), esc_html(implode(', ', array_map('intval', $selected_ids)))); ?> / <?php printf(esc_html__('The selected child object IDs (%s) will be used to create the relations.', 'textdomain'), esc_html(implode(', ', array_map('intval', $selected_ids)))); ?></li>
                    <li><?php esc_html_e('Haz clic en el botón "Insertar Relaciones".', 'textdomain'); ?> / <?php esc_html_e('Click the "Insert Relations" button.', 'textdomain'); ?></li>
                </ol>
            <?php endif; ?>
        </div>

        <form method="post">
            <?php wp_nonce_field('rmj_insert_nonce', 'rmj_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="tabla"><?php esc_html_e('Seleccionar Tabla', 'textdomain'); ?></label></th>
                    <td>
                        <select name="tabla" id="tabla" required>
                            <?php
                            foreach ($tables as $table) {
                                echo '<option value="' . esc_attr($table) . '">' . esc_html($table) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="rel_id"><?php esc_html_e('Rel ID', 'textdomain'); ?></label></th>
                    <td><input name="rel_id" type="text" id="rel_id" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="parent_rel"><?php esc_html_e('Parent Rel', 'textdomain'); ?></label></th>
                    <td><input name="parent_rel" type="number" id="parent_rel" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="parent_object_id"><?php esc_html_e('Parent Object ID', 'textdomain'); ?></label></th>
                    <td><input name="parent_object_id" type="number" id="parent_object_id" required></td>
                </tr>
                <?php if (empty($selected_ids)): ?>
                    <tr>
                        <th scope="row"><label for="child_object_id_start"><?php esc_html_e('Child Object ID (Desde)', 'textdomain'); ?></label></th>
                        <td><input name="child_object_id_start" type="number" id="child_object_id_start" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="child_object_id_end"><?php esc_html_e('Child Object ID (Hasta)', 'textdomain'); ?></label></th>
                        <td><input name="child_object_id_end" type="number" id="child_object_id_end" required></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th scope="row"><?php esc_html_e('IDs Seleccionados', 'textdomain'); ?></th>
                        <td><?php echo esc_html(implode(', ', array_map('intval', $selected_ids))); ?></td>
                    </tr>
                <?php endif; ?>
            </table>
            <p class="submit"><input type="submit" name="rmj_submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Insertar Relaciones', 'textdomain'); ?>"></p>
        </form>
    </div>
    <?php
}

// Función para manejar la inserción masiva
function rmj_insertar_relaciones_masivas() {
    global $wpdb;

    // Recoger los datos del formulario
    $tabla = sanitize_text_field($_POST['tabla']);
    $rel_id = sanitize_text_field($_POST['rel_id']);
    $parent_rel = intval($_POST['parent_rel']);
    $parent_object_id = intval($_POST['parent_object_id']);
    $child_object_id_start = intval($_POST['child_object_id_start']);
    $child_object_id_end = intval($_POST['child_object_id_end']);

    // Verificar si hay IDs seleccionados desde una acción masiva
    $selected_ids = get_option('rmj_selected_cct_items', []);

    if (!empty($selected_ids)) {
        foreach ($selected_ids as $child_object_id) {
            $wpdb->insert(
                $tabla,
                array(
                    'created' => current_time('mysql'),
                    'rel_id' => $rel_id,
                    'parent_rel' => $parent_rel,
                    'parent_object_id' => $parent_object_id,
                    'child_object_id' => $child_object_id
                ),
                array(
                    '%s',
                    '%s',
                    '%d',
                    '%d',
                    '%d'
                )
            );
        }
        // Limpiar los IDs seleccionados
        delete_option('rmj_selected_cct_items');
    } else {
        // Si no hay IDs seleccionados, utilizar el rango de IDs
        for ($child_object_id = $child_object_id_start; $child_object_id <= $child_object_id_end; $child_object_id++) {
            $wpdb->insert(
                $tabla,
                array(
                    'created' => current_time('mysql'),
                    'rel_id' => $rel_id,
                    'parent_rel' => $parent_rel,
                    'parent_object_id' => $parent_object_id,
                    'child_object_id' => $child_object_id
                ),
                array(
                    '%s',
                    '%s',
                    '%d',
                    '%d',
                    '%d'
                )
            );
        }
    }

    echo '<div class="notice notice-success is-dismissible"><p>' . sprintf(esc_html__('Relaciones insertadas exitosamente en la tabla %s.', 'textdomain'), esc_html($tabla)) . '</p></div>';
}

// Registro de una acción masiva en las listas de CCT
add_action('admin_init', 'rmj_register_dynamic_cct_bulk_actions');

function rmj_register_dynamic_cct_bulk_actions() {
    // Hook para añadir acciones masivas en cualquier lista de entradas
    add_filter('bulk_actions-edit', 'rmj_add_bulk_action_for_relation_dynamic');
    add_filter('handle_bulk_actions-edit', 'rmj_cct_bulk_action_handler_dynamic', 10, 3);
}

function rmj_add_bulk_action_for_relation_dynamic($bulk_actions) {
    $screen = get_current_screen();

    // Verificar si estamos en una página de administración de un CCT de JetEngine
    if ($screen && isset($screen->post_type) && strpos($screen->id, 'jet-cct-') === 0) {
        $bulk_actions['relacionar_cct_items'] = __('Relacionar CCT Items', 'textdomain');
    }

    return $bulk_actions;
}

// Manejar la acción masiva cuando se selecciona y procesa
function rmj_cct_bulk_action_handler_dynamic($redirect_to, $doaction, $post_ids) {
    if ($doaction !== 'relacionar_cct_items') {
        return $redirect_to;
    }

    // Guardar los IDs seleccionados en una opción para que se puedan usar en la siguiente página
    update_option('rmj_selected_cct_items', $post_ids);

    // Redirigir a la página del formulario de relaciones masivas
    return add_query_arg(array('page' => 'relaciones-masivas-jetengine', 'bulk_action' => 'true'), admin_url('admin.php'));
}

// Mostrar notificación de éxito después de la acción masiva
add_action('admin_notices', 'rmj_cct_relation_success_notice');

function rmj_cct_relation_success_notice() {
    if (isset($_GET['bulk_relacion_exito'])) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Acción masiva de relaciones completada exitosamente.', 'textdomain') . '</p></div>';
    }
}