<?php
/**
 * Register a meta box using a class.
 */
class Custom_Metabox {

    private $mb_id;
    private $title;
    private $post_type;
    public $html;

    /*
     *  Constructor.
     */
    public function __construct($id, $title, $post_type) {
        $this->mb_id = $id;
        $this->title = $title;
        $this->post_type = $post_type;
    }

    /*
     *  Meta box initialisation
     */
    public function init() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }

    /*
     *  Adds the meta box
     */
    public function add_metabox() {
        add_meta_box(
            $this->mb_id,
            $this->title,
            array( $this, 'metabox_html' ),
            $this->post_type,
            'advanced',
            'default'
        );
    }

    /*
     *  Metabox HTML
     */
    public function metabox_html( $post ) {
        // Add nonce for security and authentication.
        wp_nonce_field( $this->mb_id . '_nonce_action', $this->mb_id . '_nonce' );
        echo $this->html;
    }

    /*
     *  Handles saving the meta box.
     */
    public function save_metabox( $post_id, $post ) {
        $nonce_name   = isset( $_POST[$this->mb_id . '_nonce'] ) ? $_POST[$this->mb_id . '_nonce'] : '';
        $nonce_action = $this->mb_id . '_nonce_action';

        if ( ! isset( $nonce_name ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
    }
}