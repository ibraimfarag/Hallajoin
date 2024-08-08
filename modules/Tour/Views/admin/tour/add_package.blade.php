<style>
    .package-container {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 20px;
        background-color: #f9f9f9;
        position: relative;
    }
    .remove-package, .remove-person {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        color: red;
        font-size: 18px;
    }
    .remove-person {
        top: 40px;
    }
    .btn-add {
        margin-bottom: 20px;
    }
    .package-header, .person-type-header {
        background-color: #007bff;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .form-group label {
        font-weight: bold;
    }
    .form-control {
        border-radius: 5px;
    }
    .btn {
        border-radius: 5px;
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4">Add Packages</h2>
    <form id="packagesForm" method="post" action="">
        @csrf
        <div id="packagesWrapper">
            <!-- Package fields will be dynamically added here -->
        </div>
        <button type="button" class="btn btn-primary btn-add" id="addPackage"><i class="fas fa-plus"></i> Add Package</button>
        <input type="hidden" name="packages" id="packagesJson">
        <button type="submit" class="btn btn-success mt-3"><i class="fas fa-save"></i> Submit</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        let packageCount = 0;
        let personCount = 0;

        // Function to add a new package form
        function addPackage() {
            packageCount++;
            $('#packagesWrapper').append(`
                <div class="package-container" id="package-${packageCount}">
                    <div class="package-header">Package ${packageCount}</div>
                    <div class="form-group">
                        <label for="name-${packageCount}">Name:</label>
                        <input type="text" class="form-control" id="name-${packageCount}" name="packages[${packageCount}][name]" required>
                    </div>
                    <div class="form-group">
                        <label for="price-${packageCount}">Price:</label>
                        <input type="number" class="form-control" id="price-${packageCount}" name="packages[${packageCount}][price]" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="sale-price-${packageCount}">Sale Price:</label>
                        <input type="number" class="form-control" id="sale-price-${packageCount}" name="packages[${packageCount}][sale_price]" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="availability-${packageCount}">Availability Date & Time:</label>
                        <input type="datetime-local" class="form-control" id="availability-${packageCount}" name="packages[${packageCount}][availability]" required>
                    </div>
                    <div id="personTypes-${packageCount}">
                        <!-- Person types will be dynamically added here -->
                    </div>
                    <button type="button" class="btn btn-secondary" id="addPersonType-${packageCount}" data-package-id="${packageCount}"><i class="fas fa-plus"></i> Add Person Type</button>
                    <i class="fas fa-times remove-package" data-id="${packageCount}"></i>
                </div>
            `);
        }

        // Function to add a new person type form
        function addPersonType(packageId) {
            personCount++;
            $(`#personTypes-${packageId}`).append(`
                <div class="person-type-container" id="person-${personCount}">
                    <div class="person-type-header">Person Type ${personCount}</div>
                    <div class="form-group">
                        <label for="person-name-${personCount}">Name:</label>
                        <input type="text" class="form-control" id="person-name-${personCount}" name="packages[${packageId}][person_types][${personCount}][name]" required>
                    </div>
                    <div class="form-group">
                        <label for="person-desc-${personCount}">Description:</label>
                        <textarea class="form-control" id="person-desc-${personCount}" name="packages[${packageId}][person_types][${personCount}][description]" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="person-price-${personCount}">Price:</label>
                        <input type="number" class="form-control" id="person-price-${personCount}" name="packages[${packageId}][person_types][${personCount}][price]" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="person-sale-price-${personCount}">Sale Price:</label>
                        <input type="number" class="form-control" id="person-sale-price-${personCount}" name="packages[${packageId}][person_types][${personCount}][sale_price]" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="person-max-${personCount}">Maximum:</label>
                        <input type="number" class="form-control" id="person-max-${personCount}" name="packages[${packageId}][person_types][${personCount}][max]" required>
                    </div>
                    <i class="fas fa-times remove-person" data-id="${personCount}"></i>
                </div>
            `);
        }

        // Add initial package
        addPackage();

        // Add new package when button is clicked
        $('#addPackage').click(function() {
            addPackage();
        });

        // Add new person type when button is clicked
        $(document).on('click', '[id^=addPersonType-]', function() {
            let packageId = $(this).data('package-id');
            addPersonType(packageId);
        });

        // Remove package when button is clicked
        $(document).on('click', '.remove-package', function() {
            const id = $(this).data('id');
            $(`#package-${id}`).remove();
        });

        // Remove person type when button is clicked
        $(document).on('click', '.remove-person', function() {
            const id = $(this).data('id');
            $(`#person-${id}`).remove();
        });

        // Form submission
        $('#packagesForm').submit(function(e) {
            e.preventDefault();

            let packagesArray = [];
            $('#packagesWrapper .package-container').each(function() {
                let packageData = {};
                $(this).find('input, textarea').each(function() {
                    packageData[$(this).attr('name').match(/\[([^\]]+)\]/)[1]] = $(this).val();
                });
                packageData['person_types'] = [];
                $(this).find('.person-type-container').each(function() {
                    let personTypeData = {};
                    $(this).find('input, textarea').each(function() {
                        personTypeData[$(this).attr('name').match(/\[([^\]]+)\]/)[1]] = $(this).val();
                    });
                    packageData['person_types'].push(personTypeData);
                });
                packagesArray.push(packageData);
            });

            $('#packagesJson').val(JSON.stringify(packagesArray));
            this.submit();
        });
    });
</script>
