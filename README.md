# JetEngine Bulk Relations

**Version:** 1.2

**Author:** Francisco Sánchez

**Description:** This plugin allows you to insert bulk relations directly into your JetEngine relation tables from the WordPress backend, including bulk actions in Custom Content Types (CCTs).

## Installation

1.  **Download** the plugin ZIP file.
2.  From your WordPress admin dashboard, navigate to **Plugins** > **Add New**.
3.  Click **Upload Plugin** and select the ZIP file you downloaded.
4.  Click **Install Now**.
5.  Once installed, click **Activate Plugin**.

## Usage

Once activated, the plugin adds a new section to your WordPress admin menu called **Bulk Relations**.

### Accessing the Bulk Relations Page

Navigate to **Bulk Relations** > **Bulk Relations** in your WordPress admin menu.

### Manually Inserting Bulk Relations

1.  On the **Bulk Relations** page, you will see a set of fields:
    * **Select Table:** Choose the JetEngine relations table you want to modify. Tables starting with `wp_jet_rel_` will be listed.
    * **Rel ID:** Enter the ID of the JetEngine relation.
    * **Parent Rel:** Enter the parent relation ID.
    * **Parent Object ID:** Enter the ID of the parent object for the relations.
    * **Child Object ID (From):** Specify the starting ID for a range of child objects you want to relate.
    * **Child Object ID (To):** Specify the ending ID for a range of child objects you want to relate. The plugin will create relations for all child object IDs within this range (inclusive).
    * **Selected IDs:** This section will appear if you have initiated the bulk relation process from a CCT bulk action. It will display the IDs of the selected CCT items, and the "Child Object ID (From)" and "Child Object ID (To)" fields will be hidden.
2.  Fill in the required fields.
3.  Click the **Insert Relations** button.
4.  A success message will appear, indicating the table where the relations were inserted.

### Using Bulk Actions in Custom Content Types (CCTs)

This plugin integrates a "Relate CCT Items" bulk action into your JetEngine CCT list tables.

1.  Go to the list of your JetEngine Custom Content Type entries in the WordPress admin.
2.  Select the items you want to relate using the checkboxes next to each item.
3.  In the **Bulk actions** dropdown menu at the top or bottom of the list, select **Relate CCT Items**.
4.  Click the **Apply** button.
5.  You will be automatically redirected to the **Bulk Relations** page. The IDs of the CCT items you selected will be automatically populated and displayed under "Selected IDs".
6.  On the **Bulk Relations** page, choose the **Select Table**, enter the **Rel ID**, **Parent Rel**, and **Parent Object ID** for the relation you want to create.
7.  Click the **Insert Relations** button.
8.  After the relations are inserted, you will be redirected back to your CCT list, and a success notice will be displayed.

### Instructions on the Bulk Relations Page

At the top of the **Bulk Relations** page, you will find a section with instructions in both Spanish and English to guide you through the process:

**Cómo usar la Inserción Masiva de Relaciones / How to Use Bulk Relation Insertion**

* **Manual Insertion:** Provides a step-by-step guide for manually filling out the form to insert relations based on a child object ID range.
* **Bulk Action from CCT:** Provides instructions for users who have navigated to the page after performing the "Relate CCT Items" bulk action on a CCT, explaining that the selected IDs will be used.

## Important Notes

* **Permissions:** You need to have the `manage_options` capability to access and use this plugin's features.
* **JetEngine Dependency:** This plugin requires the JetEngine plugin by Crocoblock to be installed and active.
* **Database Backup:** It is highly recommended to back up your WordPress database before performing bulk operations.
* **Plugin Text Domain:** This plugin uses the text domain `textdomain` for translations.

## Author

Francisco Sánchez
