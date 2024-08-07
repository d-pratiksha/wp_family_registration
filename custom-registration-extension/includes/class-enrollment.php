<?php
class Enrollment {
    public static function init() {
        add_action( 'hivepress/v1/models/listing/update', [ __CLASS__, 'check_and_enroll_family_member' ], 10, 2 );
    }

   function check_and_enroll_family_member($listing_id, $family_member_id) {
    // Get listing details
    $listing = get_post($listing_id);
    $age = get_post_meta($listing_id, '_listing_age', true);
    $gender = get_post_meta($listing_id, '_listing_gender', true);
    
    // Get family member details
    $member_age = get_post_meta($family_member_id, '_family_member_age', true);
    $member_gender = get_post_meta($family_member_id, '_family_member_gender', true);
    
    // Check eligibility
    if (($age === $member_age) && ($gender === $member_gender)) {
        // Enroll member
        update_post_meta($listing_id, '_enrolled_family_members', $family_member_id);
        return true;
    }
    
    return false;
}

}
