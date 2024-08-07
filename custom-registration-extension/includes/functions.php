<?php
// Shortcode to display the registration selection form
function registration_selection_form() {
    ob_start();
    ?>
    <form method="post" action="" class="registration-form">
        <h3>Choose Registration Type</h3>
        <div class="form-group">
            <input type="radio" id="individual" name="registration_type" value="individual" checked>
            <label for="individual">Individual Registration</label>
        </div>
        <div class="form-group">
            <input type="radio" id="family" name="registration_type" value="family">
            <label for="family">Family Registration</label>
        </div>
        <div class="form-group">
            <input type="submit" name="select_registration_type" value="Continue" class="btn btn-primary">
        </div>
    </form>
    <?php
    if (isset($_POST['select_registration_type'])) {
        $registration_type = sanitize_text_field($_POST['registration_type']);
        if ($registration_type === 'individual') {
            echo individual_registration_form();
        } elseif ($registration_type === 'family') {
            echo family_registration_form();
        }
    }
    return ob_get_clean();
}

// Shortcode to display individual registration form
function individual_registration_form() {
    ob_start();
    ?>
    <form method="post" action="">
        <input type="hidden" name="listing_id" value="<?php echo esc_attr(get_the_ID()); ?>">
        <label for="individual_name">Name:</label>
        <input type="text" id="individual_name" name="individual_name" required>
        <br>
        <label for="individual_age">Age:</label>
        <input type="number" id="individual_age" name="individual_age" required>
        <br>
        <label for="individual_gender">Gender:</label>
        <select id="individual_gender" name="individual_gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        <br>
        <input type="submit" name="submit_individual" value="Register">
    </form>
    <?php
    if (isset($_POST['submit_individual'])) {
        handle_individual_registration();
    }
    return ob_get_clean();
}

// Handle individual registration
function handle_individual_registration() {
    if (isset($_POST['individual_name'], $_POST['individual_age'], $_POST['individual_gender'], $_POST['listing_id'])) {
        $name = sanitize_text_field($_POST['individual_name']);
        $age = intval($_POST['individual_age']);
        $gender = sanitize_text_field($_POST['individual_gender']);
        $listing_id = intval($_POST['listing_id']);

        $post_id = wp_insert_post(array(
            'post_title'  => $name,
            'post_type'   => 'program_registration',
            'post_status' => 'publish',
        ));

        if ($post_id) {
            update_post_meta($post_id, 'age', $age);
            update_post_meta($post_id, 'gender', $gender);
            update_post_meta($post_id, 'linked_listing_id', $listing_id);
            echo '<p>Registration successful for listing ID ' . $listing_id . '.</p>';
        }
    } else {
        echo '<p>Error: Required fields are missing.</p>';
    }
}

// Shortcode to display family registration form
function family_registration_form() {
    ob_start();
    ?>
    <form method="post" action="">
        <input type="hidden" name="listing_id" value="<?php echo esc_attr(get_the_ID()); ?>">
        <h3>Family Registration</h3>
        <div id="family_members">
            <div class="family_member">
                <h4>Family Member 1</h4>
                <label for="family_member_1_name">Name:</label>
                <input type="text" id="family_member_1_name" name="family_members[0][name]" required>
                <br>
                <label for="family_member_1_age">Age:</label>
                <input type="number" id="family_member_1_age" name="family_members[0][age]" required>
                <br>
                <label for="family_member_1_gender">Gender:</label>
                <select id="family_member_1_gender" name="family_members[0][gender]" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <br>
                <label for="family_member_1_relation">Relation:</label>
                <select id="family_member_1_relation" name="family_members[0][relation]" required>
                    <option value="father">Father</option>
                    <option value="mother">Mother</option>
                    <option value="daughter">Daughter</option>
                    <option value="son">Son</option>
                </select>
                <br>
            </div>
        </div>
        <button type="button" id="add_family_member">Add Family Member</button>
        <br>
        <input type="submit" name="submit_family" value="Register">
    </form>
    <script>
        document.getElementById('add_family_member').addEventListener('click', function() {
            var familyMembersDiv = document.getElementById('family_members');
            var newIndex = familyMembersDiv.children.length;
            var newMemberDiv = document.createElement('div');
            newMemberDiv.className = 'family_member';
            newMemberDiv.innerHTML = `
                <h4>Family Member ${newIndex + 1}</h4>
                <label for="family_member_${newIndex}_name">Name:</label>
                <input type="text" id="family_member_${newIndex}_name" name="family_members[${newIndex}][name]" required>
                <br>
                <label for="family_member_${newIndex}_age">Age:</label>
                <input type="number" id="family_member_${newIndex}_age" name="family_members[${newIndex}][age]" required>
                <br>
                <label for="family_member_${newIndex}_gender">Gender:</label>
                <select id="family_member_${newIndex}_gender" name="family_members[${newIndex}][gender]" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <br>
                <label for="family_member_${newIndex}_relation">Relation:</label>
                <select id="family_member_${newIndex}_relation" name="family_members[${newIndex}][relation]" required>
                    <option value="father">Father</option>
                    <option value="mother">Mother</option>
                    <option value="daughter">Daughter</option>
                    <option value="son">Son</option>
                </select>
                <br>
            `;
            familyMembersDiv.appendChild(newMemberDiv);
        });
    </script>
    <?php
    if (isset($_POST['submit_family'])) {
        handle_family_registration();
    }
    return ob_get_clean();
}

// Handle family registration
function handle_family_registration() {
    if (isset($_POST['family_members']) && is_array($_POST['family_members']) && isset($_POST['listing_id'])) {
        $listing_id = intval($_POST['listing_id']);
        $family_post_id = wp_insert_post(array(
            'post_title'  => 'Family Registration',
            'post_type'   => 'program_registration',
            'post_status' => 'publish',
        ));

        foreach ($_POST['family_members'] as $member) {
            if (isset($member['name'], $member['age'], $member['gender'], $member['relation'])) {
                $name = sanitize_text_field($member['name']);
                $age = intval($member['age']);
                $gender = sanitize_text_field($member['gender']);
                $relation = sanitize_text_field($member['relation']);

                $member_post_id = wp_insert_post(array(
                    'post_title'  => $name,
                    'post_type'   => 'family_member',
                    'post_status' => 'publish',
                    'post_parent' => $family_post_id,
                ));

                if ($member_post_id) {
                    update_post_meta($member_post_id, 'age', $age);
                    update_post_meta($member_post_id, 'gender', $gender);
                    update_post_meta($member_post_id, 'relation', $relation);
                }
            }
        }

        update_post_meta($family_post_id, 'linked_listing_id', $listing_id);
        echo '<p>Family registration successful for listing ID ' . $listing_id . '.</p>';
    } else {
        echo '<p>Error: Family members data is missing or malformed.</p>';
    }
}

// Register custom post types
function register_custom_post_types() {
    register_post_type('program_registration', array(
        'labels' => array(
            'name' => 'Program Registrations',
            'singular_name' => 'Program Registration',
        ),
        'public' => false,
        'has_archive' => false,
        'supports' => array('title', 'custom-fields'),
    ));
    
    register_post_type('family_member', array(
        'labels' => array(
            'name' => 'Family Members',
            'singular_name' => 'Family Member',
        ),
        'public' => false,
        'has_archive' => false,
        'supports' => array('title', 'custom-fields'),
    ));
}
add_action('init', 'register_custom_post_types');

// Register shortcode
function register_registration_shortcodes() {
    add_shortcode('registration_selection_form', 'registration_selection_form');
}
add_action('init', 'register_registration_shortcodes');
?>
