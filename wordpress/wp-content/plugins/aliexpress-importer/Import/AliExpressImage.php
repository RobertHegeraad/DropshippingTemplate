<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 30-12-2016
 * Time: 15:33
 */

class AliExpressImage {

    public function ConvertImageTo640x640($aliExpressImageUrl) {
        $extension = ".jpg_640x640.jpg";

        $productName = basename($aliExpressImageUrl);

        $parsed = parse_url($aliExpressImageUrl);

        $uri = explode($productName, $parsed['path'])[0];

        $convertedUrl = $parsed['scheme'] . "://" . $parsed['host'] . $uri . basename($aliExpressImageUrl, '.jpg_50x50.jpg') . $extension;

        return $convertedUrl;
    }

    public function UploadProductThumbnail($aliExpressImageUrl, $post_id) {
        $resizedUrl = $this->ConvertImageTo640x640($aliExpressImageUrl);
        $upload_file = wp_upload_bits("temp-" . $post_id . ".jpg", null, file_get_contents($resizedUrl));

        // $filename should be the path to a file in the upload directory.
        $filename = $upload_file['file'];

        // Check the type of file. We'll use this as the 'post_mime_type'.
        $filetype = wp_check_filetype( basename( $filename ), null );

        $attachment = array(
            'guid'           => wp_upload_dir()['url'] . '/' . basename( $filename ),
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );

        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        wp_update_attachment_metadata( $attach_id, $attach_data );

        unlink($upload_file['file']);

        set_post_thumbnail($post_id, $attach_id);
    }

    function UploadProductGallery($productImages, $post_id) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $productImagesIds = array();
        foreach($productImages as $productImage) {
            $resizedUrl = $this->ConvertImageTo640x640($productImage);
            $tmp = download_url($resizedUrl);
            $file_array['name'] = basename($resizedUrl);
            $file_array['tmp_name'] = $tmp;
            $productImagesIds[] = media_handle_sideload( $file_array, $post_id, 'desc' );
        }
        update_post_meta($post_id, '_product_image_gallery', implode(",", $productImagesIds));
    }
}