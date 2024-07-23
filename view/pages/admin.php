<div class="wrap">
    <h1>SOM Referral Reach Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('som_referral_reach_settings_group');
        do_settings_sections('som_referral_reach_settings_group');
        $conditions = get_option('referral_conditions', []);
        ?>
        <div id="conditions-container">
            <?php foreach ($conditions as $index => $condition) : ?>
                <div class="condition-item" data-index="<?php echo $index; ?>">
                    <h4>Condition <?php echo $index + 1; ?></h4>
                    <p>
                        <label>Category:</label>
                        <select class="condition-category" name="referral_conditions[<?php echo $index; ?>][category]">
                            <option value="on_review" <?php selected($condition['category'], 'on_review'); ?>>On Review</option>
                            <option value="on_order" <?php selected($condition['category'], 'on_order'); ?>>On Order</option>
                            <option value="on_user_actions" <?php selected($condition['category'], 'on_user_actions'); ?>>On User Actions</option>
                            <option value="on_custom_action" <?php selected($condition['category'], 'on_custom_action'); ?>>On Custom Action</option>
                        </select>
                    </p>
                    <p class="trigger-row">
                        <label>Trigger:</label>
                        <select class="condition-trigger" name="referral_conditions[<?php echo $index; ?>][trigger]" style="display: none;">
                            <!-- Trigger options will be populated by JavaScript -->
                        </select>
                        <input type="text" class="custom-action-field" name="referral_conditions[<?php echo $index; ?>][custom_action]" value="<?php echo esc_attr($condition['custom_action']); ?>" style="display: none;" />
                    </p>
                    <p class="points-row">
                        <label>Points:</label>
                        <input type="number" name="referral_conditions[<?php echo $index; ?>][points]" value="<?php echo esc_attr($condition['points']); ?>" />
                    </p>
                    <p class="min-price-row">
                        <label>Minimum Price:</label>
                        <input type="number" name="referral_conditions[<?php echo $index; ?>][min_price]" value="<?php echo esc_attr($condition['min_price']); ?>" />
                    </p>
                    <p class="custom-action-row" style="display: none;">
                        <label>Custom Action:</label>
                        <!-- Removed custom action text field here -->
                    </p>
                    <button type="button" class="remove-condition button">Remove Condition</button>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-condition" class="button">Add Condition</button>
        <?php submit_button(); ?>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let conditionsContainer = document.getElementById('conditions-container');
        let addConditionButton = document.getElementById('add-condition');
        const triggers = {
            on_review: ['on_approved', 'on_disapproved', 'on_approved_and_purchased'],
            on_order: ['pending', 'processing', 'completed', 'cancelled', 'refunded'],
            on_user_actions: ['on_register', 'on_sign_in', 'on_birthday'],
            on_custom_action: []
        };

        function populateTriggerOptions(selectElement, category, selectedValue) {
            selectElement.innerHTML = '';
            if (triggers[category]) {
                triggers[category].forEach(trigger => {
                    let option = document.createElement('option');
                    option.value = trigger;
                    option.textContent = trigger.replace(/_/g, ' ');
                    if (trigger === selectedValue) {
                        option.selected = true;
                    }
                    selectElement.appendChild(option);
                });
            }
        }

        function toggleFields(conditionItem) {
            const category = conditionItem.querySelector('.condition-category').value;
            const triggerRow = conditionItem.querySelector('.trigger-row');
            const pointsRow = conditionItem.querySelector('.points-row');
            const minPriceRow = conditionItem.querySelector('.min-price-row');
            const customActionField = conditionItem.querySelector('.custom-action-field');
            const triggerSelect = conditionItem.querySelector('.condition-trigger');

            if (category === 'on_custom_action') {
                triggerSelect.style.display = 'none';
                customActionField.style.display = 'inline-block';
            } else {
                triggerSelect.style.display = 'inline-block';
                customActionField.style.display = 'none';
                populateTriggerOptions(triggerSelect, category, triggerSelect.value);
            }

            triggerRow.style.display = 'block';
            pointsRow.style.display = 'block';
            minPriceRow.style.display = category === 'on_order' ? 'block' : 'none';
        }



        function addCondition(index, condition = {}) {
            let conditionItem = document.createElement('div');
            conditionItem.classList.add('condition-item');
            conditionItem.dataset.index = index;
            conditionItem.innerHTML = `
        <h4>Condition ${index + 1}</h4>
        <p>
            <label>Category:</label>
            <select class="condition-category" name="referral_conditions[${index}][category]">
                <option value="on_review" ${condition.category === 'on_review' ? 'selected' : ''}>On Review</option>
                <option value="on_order" ${condition.category === 'on_order' ? 'selected' : ''}>On Order</option>
                <option value="on_user_actions" ${condition.category === 'on_user_actions' ? 'selected' : ''}>On User Actions</option>
                <option value="on_custom_action" ${condition.category === 'on_custom_action' ? 'selected' : ''}>On Custom Action</option>
            </select>
        </p>
        <p class="trigger-row">
            <label>Trigger:</label>
            <select class="condition-trigger" name="referral_conditions[${index}][trigger]">
                <!-- Trigger options will be populated by JavaScript -->
            </select>
            <input type="text" class="custom-action-field" name="referral_conditions[${index}][custom_action]" value="${condition.custom_action || ''}" style="display: none;" />
        </p>
        <p class="points-row">
            <label>Points:</label>
            <input type="number" name="referral_conditions[${index}][points]" value="${condition.points || ''}" />
        </p>
        <p class="min-price-row" style="display: none;">
            <label>Minimum Price:</label>
            <input type="number" name="referral_conditions[${index}][min_price]" value="${condition.min_price || ''}" />
        </p>
        <button type="button" class="remove-condition button">Remove Condition</button>
        <hr>
    `;
            conditionsContainer.appendChild(conditionItem);

            const categorySelect = conditionItem.querySelector('.condition-category');
            categorySelect.value = condition.category || 'on_review';

            const triggerSelect = conditionItem.querySelector('.condition-trigger');
            const customActionField = conditionItem.querySelector('.custom-action-field');

            categorySelect.addEventListener('change', function() {
                toggleFields(conditionItem);
            });

            toggleFields(conditionItem);

            if (condition.category === 'on_custom_action') {
                customActionField.value = condition.custom_action || '';
            } else {
                triggerSelect.value = condition.trigger || '';
            }

            conditionItem.querySelector('.remove-condition').addEventListener('click', function() {
                conditionItem.remove();
                updateConditionIndices();
            });
        }

        function updateConditionIndices() {
            let conditionItems = document.querySelectorAll('.condition-item');
            conditionItems.forEach((item, index) => {
                item.dataset.index = index;
                item.querySelector('h4').innerText = `Condition ${index + 1}`;
                item.querySelector('.condition-category').name = `referral_conditions[${index}][category]`;
                item.querySelector('.condition-trigger').name = `referral_conditions[${index}][trigger]`;
                item.querySelector('.points-row input').name = `referral_conditions[${index}][points]`;
                item.querySelector('.min-price-row input').name = `referral_conditions[${index}][min_price]`;
                item.querySelector('.custom-action-field').name = `referral_conditions[${index}][custom_action]`;
            });
        }

        addConditionButton.addEventListener('click', function() {
            let index = conditionsContainer.children.length;
            addCondition(index);
        });

        // Initialize existing conditions
        document.querySelectorAll('.condition-item').forEach((item, index) => {
            const category = item.querySelector('.condition-category').value;
            const trigger = item.querySelector('.condition-trigger').value;
            const points = item.querySelector('.points-row input').value;
            const minPrice = item.querySelector('.min-price-row input').value;
            const customAction = item.querySelector('.custom-action-field').value;

            addCondition(index, {
                category: category,
                trigger: trigger,
                points: points,
                min_price: minPrice,
                custom_action: customAction
            });

            item.remove(); // Remove the original item
        });
    });
</script>