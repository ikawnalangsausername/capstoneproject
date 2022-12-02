$(document).ready(function () {
	$('#changePasswordVisibilityToggler').click(function () {
		// Password toggle
		if ($('#newPassword').prop('type') === 'password') {
			$('#newPassword').prop('type', 'text');
			$('#newPasswordConfirmation').prop('type', 'text');
		} else {
			$('#newPassword').prop('type', 'password');
			$('#newPasswordConfirmation').prop('type', 'password');
		}
	});

	// Creates datatable for employees
	$('#employeesAccountTable').DataTable();
	$('#soloParentsTable').DataTable();
	$('#pdfTable').DataTable();

	// Load date and time
	setInterval(function () {
		$('#dateTime').load('includes/dateTime.php');
	}, 1000);
});

// Password toggle
function togglePasswordVisibilityInSignUp() {
	let passwordField = document.getElementById('password');
	let confirmationPasswordField = document.getElementById(
		'passwordConfirmation'
	);

	if (
		passwordField.type === 'password' ||
		confirmationPasswordField.type === 'password'
	) {
		passwordField.type = 'text';
		confirmationPasswordField.type = 'text';
	} else {
		passwordField.type = 'password';
		confirmationPasswordField.type = 'password';
	}
}

function clearContents(elementID) {
	document.getElementById(elementID).innerHTML = '';
}

function generateChildFields() {
	clearContents('childFields');
	let numberOfChild = document.getElementById('numberOfChild').value;
	let childFieldsDiv = document.getElementById('childFields');

	for (let i = 0; i < numberOfChild; i++) {
		let childNumber = i + 1;

		let childWrapperDiv = document.createElement('div');
		childWrapperDiv.id = 'child' + childNumber + 'Div';

		let nameFieldDiv = document.createElement('div');
		nameFieldDiv.className = 'row g-2 mt-2';

		let childFieldHeader = document.createElement('h4');
		childFieldHeader.className = 'fw-bold mb-2';
		childFieldHeader.innerHTML = 'Child ' + childNumber + ' Details';

		let firstNameDiv = document.createElement('div');
		firstNameDiv.className = 'col-lg-4 mb-3';

		let firstNameLabel = document.createElement('label');
		firstNameLabel.for = 'child' + childNumber + 'FirstName';
		firstNameLabel.className = 'form-label';
		firstNameLabel.innerHTML = 'First Name';

		let firstNameInput = document.createElement('input');
		firstNameInput.type = 'text';
		firstNameInput.className = 'form-control';
		firstNameInput.id = 'child' + childNumber + 'FirstName';
		firstNameInput.name = 'child' + childNumber + 'FirstName';
		firstNameInput.placeholder = 'Pedro';

		let firstNameErrorMessage = document.createElement('span');
		firstNameErrorMessage.id =
			'child' + childNumber + 'FirstNameErrorMessage';
		firstNameErrorMessage.className = 'error-message text-danger';

		firstNameDiv.appendChild(firstNameLabel);
		firstNameDiv.appendChild(firstNameInput);
		firstNameDiv.appendChild(firstNameErrorMessage);

		let middleNameDiv = document.createElement('div');
		middleNameDiv.className = 'col-lg-4 mb-3';

		let middleNameLabel = document.createElement('label');
		middleNameLabel.for = 'child' + childNumber + 'MiddleName';
		middleNameLabel.className = 'form-label';
		middleNameLabel.innerHTML = 'Middle Name';

		let middleNameInput = document.createElement('input');
		middleNameInput.type = 'text';
		middleNameInput.className = 'form-control';
		middleNameInput.id = 'child' + childNumber + 'MiddleName';
		middleNameInput.name = 'child' + childNumber + 'MiddleName';
		middleNameInput.placeholder = 'San Juan';

		let middleNameErrorMessage = document.createElement('span');
		middleNameErrorMessage.id =
			'child' + childNumber + 'MiddleNameErrorMessage';
		middleNameErrorMessage.className = 'error-message text-danger';

		middleNameDiv.appendChild(middleNameLabel);
		middleNameDiv.appendChild(middleNameInput);
		middleNameDiv.appendChild(middleNameErrorMessage);

		let lastNameDiv = document.createElement('div');
		lastNameDiv.className = 'col-lg-4 mb-3';

		let lastNameLabel = document.createElement('label');
		lastNameLabel.for = 'child' + childNumber + 'LastName';
		lastNameLabel.className = 'form-label';
		lastNameLabel.innerHTML = 'Last Name';

		let lastNameInput = document.createElement('input');
		lastNameInput.type = 'text';
		lastNameInput.className = 'form-control';
		lastNameInput.id = 'child' + childNumber + 'LastName';
		lastNameInput.name = 'child' + childNumber + 'LastName';
		lastNameInput.placeholder = 'Dela Cruz';

		let lastNameErrorMessage = document.createElement('span');
		lastNameErrorMessage.id =
			'child' + childNumber + 'LastNameErrorMessage';
		lastNameErrorMessage.className = 'error-message text-danger';

		lastNameDiv.appendChild(lastNameLabel);
		lastNameDiv.appendChild(lastNameInput);
		lastNameDiv.appendChild(lastNameErrorMessage);

		nameFieldDiv.appendChild(childFieldHeader);
		nameFieldDiv.appendChild(firstNameDiv);
		nameFieldDiv.appendChild(middleNameDiv);
		nameFieldDiv.appendChild(lastNameDiv);

		let childsOtherInfoFieldsDiv = document.createElement('div');
		childsOtherInfoFieldsDiv.className = 'row g-2';

		let childSexFieldDiv = document.createElement('div');
		childSexFieldDiv.className = 'col-lg-4 mb-3';

		let childsSexLabel = document.createElement('label');
		childsSexLabel.for = 'child' + childNumber + 'Sex';
		childsSexLabel.className = 'form-label';
		childsSexLabel.innerHTML = 'Sex';

		let childSexSelection = document.createElement('select');
		childSexSelection.className = 'form-select';
		childSexSelection.id = 'child' + childNumber + 'Sex';
		childSexSelection.name = 'child' + childNumber + 'Sex';

		let childSexSelectionPlaceholder = document.createElement('option');
		childSexSelectionPlaceholder.selected = true;
		childSexSelectionPlaceholder.disabled = true;
		childSexSelectionPlaceholder.hidden = true;
		childSexSelectionPlaceholder.innerHTML = 'Select Sex';

		let maleSexValue = document.createElement('option');
		maleSexValue.id = 'child' + childNumber + 'SexM';
		maleSexValue.value = 'M';
		maleSexValue.innerHTML = 'Male';

		let femaleSexValue = document.createElement('option');
		femaleSexValue.id = 'child' + childNumber + 'SexF';
		femaleSexValue.value = 'F';
		femaleSexValue.innerHTML = 'Female';

		let childSexErrorMessage = document.createElement('span');
		childSexErrorMessage.id = 'child' + childNumber + 'SexErrorMessage';
		childSexErrorMessage.className = 'error-message text-danger';

		childSexSelection.appendChild(childSexSelectionPlaceholder);
		childSexSelection.appendChild(maleSexValue);
		childSexSelection.appendChild(femaleSexValue);

		childSexFieldDiv.appendChild(childsSexLabel);
		childSexFieldDiv.appendChild(childSexSelection);
		childSexFieldDiv.appendChild(childSexErrorMessage);

		let birthdateFieldDiv = document.createElement('div');
		birthdateFieldDiv.className = 'col-lg-4 mb-3';

		let birthdateLabel = document.createElement('label');
		birthdateLabel.for = 'child' + childNumber + 'Birthdate';
		birthdateLabel.className = 'form-label';
		birthdateLabel.innerHTML = 'Birthdate';

		let birthdateInput = document.createElement('input');
		birthdateInput.type = 'date';
		birthdateInput.className = 'form-control';
		birthdateInput.id = 'child' + childNumber + 'Birthdate';
		birthdateInput.name = 'child' + childNumber + 'Birthdate';

		let birthdateErrorMessage = document.createElement('span');
		birthdateErrorMessage.id =
			'child' + childNumber + 'BirthdateErrorMessage';
		birthdateErrorMessage.className = 'error-message text-danger';

		birthdateFieldDiv.appendChild(birthdateLabel);
		birthdateFieldDiv.appendChild(birthdateInput);
		birthdateFieldDiv.appendChild(birthdateErrorMessage);

		let childRelationshipToSoloParentFieldDiv =
			document.createElement('div');
		childRelationshipToSoloParentFieldDiv.className = 'col-lg-4 mb-3';

		let childRelationshipToSoloParentLabel =
			document.createElement('label');
		childRelationshipToSoloParentLabel.for =
			'child' + childNumber + 'RelationshipToSoloParent';
		childRelationshipToSoloParentLabel.className = 'form-label';
		childRelationshipToSoloParentLabel.innerHTML =
			'Relationship to Solo Parent';

		let childRelationshipSelection = document.createElement('select');
		childRelationshipSelection.className = 'form-select';
		childRelationshipSelection.id =
			'child' + childNumber + 'RelationshipToSoloParent';
		childRelationshipSelection.name =
			'child' + childNumber + 'RelationshipToSoloParent';

		let childRelationshipSelectionPlaceholder =
			document.createElement('option');
		childRelationshipSelectionPlaceholder.selected = true;
		childRelationshipSelectionPlaceholder.disabled = true;
		childRelationshipSelectionPlaceholder.hidden = true;
		childRelationshipSelectionPlaceholder.innerHTML = 'Select Relationship';

		let motherRelationshipOption = document.createElement('option');
		motherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent1';
		motherRelationshipOption.value = 1;
		motherRelationshipOption.innerHTML = 'Mother';

		let fatherRelationshipOption = document.createElement('option');
		fatherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent2';
		fatherRelationshipOption.value = 2;
		fatherRelationshipOption.innerHTML = 'Father';

		let siblingRelationshipOption = document.createElement('option');
		siblingRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent3';
		siblingRelationshipOption.value = 3;
		siblingRelationshipOption.innerHTML = 'Sibling';

		let stepFatherRelationshipOption = document.createElement('option');
		stepFatherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent4';
		stepFatherRelationshipOption.value = 4;
		stepFatherRelationshipOption.innerHTML = 'Step-father';

		let stepMotherRelationshipOption = document.createElement('option');
		stepMotherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent5';
		stepMotherRelationshipOption.value = 5;
		stepMotherRelationshipOption.innerHTML = 'Step-mother';

		let stepSiblingRelationshipOption = document.createElement('option');
		stepSiblingRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent6';
		stepSiblingRelationshipOption.value = 6;
		stepSiblingRelationshipOption.innerHTML = 'Step-sibling';

		let halfSiblingRelationshipOption = document.createElement('option');
		halfSiblingRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent7';
		halfSiblingRelationshipOption.value = 7;
		halfSiblingRelationshipOption.innerHTML = 'Half-sibling';

		let fosterMotherRelationshipOption = document.createElement('option');
		fosterMotherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent8';
		fosterMotherRelationshipOption.value = 8;
		fosterMotherRelationshipOption.innerHTML = 'Foster mother';

		let fosterFatherRelationshipOption = document.createElement('option');
		fosterFatherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent9';
		fosterFatherRelationshipOption.value = 9;
		fosterFatherRelationshipOption.innerHTML = 'Foster father';

		let uncleRelationshipOption = document.createElement('option');
		uncleRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent10';
		uncleRelationshipOption.value = 10;
		uncleRelationshipOption.innerHTML = 'Uncle';

		let auntRelationshipOption = document.createElement('option');
		auntRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent11';
		auntRelationshipOption.value = 11;
		auntRelationshipOption.innerHTML = 'Aunt';

		let cousinRelationshipOption = document.createElement('option');
		cousinRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent12';
		cousinRelationshipOption.value = 12;
		cousinRelationshipOption.innerHTML = 'Cousin';

		let grandFatherRelationshipOption = document.createElement('option');
		grandFatherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent13';
		grandFatherRelationshipOption.value = 13;
		grandFatherRelationshipOption.innerHTML = 'Grandfather';

		let grandMotherRelationshipOption = document.createElement('option');
		grandMotherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent14';
		grandMotherRelationshipOption.value = 14;
		grandMotherRelationshipOption.innerHTML = 'Grandmother';

		childRelationshipSelection.appendChild(
			childRelationshipSelectionPlaceholder
		);
		childRelationshipSelection.appendChild(motherRelationshipOption);
		childRelationshipSelection.appendChild(fatherRelationshipOption);
		childRelationshipSelection.appendChild(siblingRelationshipOption);
		childRelationshipSelection.appendChild(stepFatherRelationshipOption);
		childRelationshipSelection.appendChild(stepMotherRelationshipOption);
		childRelationshipSelection.appendChild(stepSiblingRelationshipOption);
		childRelationshipSelection.appendChild(halfSiblingRelationshipOption);
		childRelationshipSelection.appendChild(fosterMotherRelationshipOption);
		childRelationshipSelection.appendChild(fosterFatherRelationshipOption);
		childRelationshipSelection.appendChild(uncleRelationshipOption);
		childRelationshipSelection.appendChild(auntRelationshipOption);
		childRelationshipSelection.appendChild(cousinRelationshipOption);
		childRelationshipSelection.appendChild(grandFatherRelationshipOption);
		childRelationshipSelection.appendChild(grandMotherRelationshipOption);

		let relationshipErrorMessage = document.createElement('span');
		relationshipErrorMessage.id =
			'child' + childNumber + 'RelationshipErrorMessage';
		relationshipErrorMessage.className = 'error-message text-danger';

		childRelationshipToSoloParentFieldDiv.appendChild(
			childRelationshipToSoloParentLabel
		);

		childRelationshipToSoloParentFieldDiv.appendChild(
			childRelationshipSelection
		);

		childRelationshipToSoloParentFieldDiv.appendChild(
			relationshipErrorMessage
		);

		childsOtherInfoFieldsDiv.appendChild(childSexFieldDiv);
		childsOtherInfoFieldsDiv.appendChild(birthdateFieldDiv);
		childsOtherInfoFieldsDiv.appendChild(
			childRelationshipToSoloParentFieldDiv
		);

		childWrapperDiv.appendChild(nameFieldDiv);
		childWrapperDiv.appendChild(childsOtherInfoFieldsDiv);

		childFieldsDiv.appendChild(childWrapperDiv);
	}
}

function generateAdditionalChildFields() {
	let numberOfChild = document.getElementById('numberOfChild').value;
	let childFieldsDiv = document.getElementById('childFields');

	for (let i = parseInt(numberOfChild) - 1; i < numberOfChild; i++) {
		let childNumber = i + 1;

		let childWrapperDiv = document.createElement('div');
		childWrapperDiv.id = 'child' + childNumber + 'Div';

		let nameFieldDiv = document.createElement('div');
		nameFieldDiv.className = 'row g-2 mt-2';

		let childFieldHeader = document.createElement('h4');
		childFieldHeader.className = 'fw-bold mb-2';
		childFieldHeader.innerHTML = 'Child ' + childNumber + ' Details';

		let firstNameDiv = document.createElement('div');
		firstNameDiv.className = 'col-lg-4 mb-3';

		let firstNameLabel = document.createElement('label');
		firstNameLabel.for = 'child' + childNumber + 'FirstName';
		firstNameLabel.className = 'form-label';
		firstNameLabel.innerHTML = 'First Name';

		let firstNameInput = document.createElement('input');
		firstNameInput.type = 'text';
		firstNameInput.className = 'form-control';
		firstNameInput.id = 'child' + childNumber + 'FirstName';
		firstNameInput.name = 'child' + childNumber + 'FirstName';
		firstNameInput.placeholder = 'Pedro';

		let firstNameErrorMessage = document.createElement('span');
		firstNameErrorMessage.id =
			'child' + childNumber + 'FirstNameErrorMessage';
		firstNameErrorMessage.className = 'error-message text-danger';

		firstNameDiv.appendChild(firstNameLabel);
		firstNameDiv.appendChild(firstNameInput);
		firstNameDiv.appendChild(firstNameErrorMessage);

		let middleNameDiv = document.createElement('div');
		middleNameDiv.className = 'col-lg-4 mb-3';

		let middleNameLabel = document.createElement('label');
		middleNameLabel.for = 'child' + childNumber + 'MiddleName';
		middleNameLabel.className = 'form-label';
		middleNameLabel.innerHTML = 'Middle Name';

		let middleNameInput = document.createElement('input');
		middleNameInput.type = 'text';
		middleNameInput.className = 'form-control';
		middleNameInput.id = 'child' + childNumber + 'MiddleName';
		middleNameInput.name = 'child' + childNumber + 'MiddleName';
		middleNameInput.placeholder = 'San Juan';

		let middleNameErrorMessage = document.createElement('span');
		middleNameErrorMessage.id =
			'child' + childNumber + 'MiddleNameErrorMessage';
		middleNameErrorMessage.className = 'error-message text-danger';

		middleNameDiv.appendChild(middleNameLabel);
		middleNameDiv.appendChild(middleNameInput);
		middleNameDiv.appendChild(middleNameErrorMessage);

		let lastNameDiv = document.createElement('div');
		lastNameDiv.className = 'col-lg-4 mb-3';

		let lastNameLabel = document.createElement('label');
		lastNameLabel.for = 'child' + childNumber + 'LastName';
		lastNameLabel.className = 'form-label';
		lastNameLabel.innerHTML = 'Last Name';

		let lastNameInput = document.createElement('input');
		lastNameInput.type = 'text';
		lastNameInput.className = 'form-control';
		lastNameInput.id = 'child' + childNumber + 'LastName';
		lastNameInput.name = 'child' + childNumber + 'LastName';
		lastNameInput.placeholder = 'Dela Cruz';

		let lastNameErrorMessage = document.createElement('span');
		lastNameErrorMessage.id =
			'child' + childNumber + 'LastNameErrorMessage';
		lastNameErrorMessage.className = 'error-message text-danger';

		lastNameDiv.appendChild(lastNameLabel);
		lastNameDiv.appendChild(lastNameInput);
		lastNameDiv.appendChild(lastNameErrorMessage);

		nameFieldDiv.appendChild(childFieldHeader);
		nameFieldDiv.appendChild(firstNameDiv);
		nameFieldDiv.appendChild(middleNameDiv);
		nameFieldDiv.appendChild(lastNameDiv);

		let childsOtherInfoFieldsDiv = document.createElement('div');
		childsOtherInfoFieldsDiv.className = 'row g-2';

		let childSexFieldDiv = document.createElement('div');
		childSexFieldDiv.className = 'col-lg-4 mb-3';

		let childsSexLabel = document.createElement('label');
		childsSexLabel.for = 'child' + childNumber + 'Sex';
		childsSexLabel.className = 'form-label';
		childsSexLabel.innerHTML = 'Sex';

		let childSexSelection = document.createElement('select');
		childSexSelection.className = 'form-select';
		childSexSelection.id = 'child' + childNumber + 'Sex';
		childSexSelection.name = 'child' + childNumber + 'Sex';

		let childSexSelectionPlaceholder = document.createElement('option');
		childSexSelectionPlaceholder.selected = true;
		childSexSelectionPlaceholder.disabled = true;
		childSexSelectionPlaceholder.hidden = true;
		childSexSelectionPlaceholder.innerHTML = 'Select Sex';

		let maleSexValue = document.createElement('option');
		maleSexValue.id = 'child' + childNumber + 'SexM';
		maleSexValue.value = 'M';
		maleSexValue.innerHTML = 'Male';

		let femaleSexValue = document.createElement('option');
		femaleSexValue.id = 'child' + childNumber + 'SexF';
		femaleSexValue.value = 'F';
		femaleSexValue.innerHTML = 'Female';

		let childSexErrorMessage = document.createElement('span');
		childSexErrorMessage.id = 'child' + childNumber + 'SexErrorMessage';
		childSexErrorMessage.className = 'error-message text-danger';

		childSexSelection.appendChild(childSexSelectionPlaceholder);
		childSexSelection.appendChild(maleSexValue);
		childSexSelection.appendChild(femaleSexValue);

		childSexFieldDiv.appendChild(childsSexLabel);
		childSexFieldDiv.appendChild(childSexSelection);
		childSexFieldDiv.appendChild(childSexErrorMessage);

		let birthdateFieldDiv = document.createElement('div');
		birthdateFieldDiv.className = 'col-lg-4 mb-3';

		let birthdateLabel = document.createElement('label');
		birthdateLabel.for = 'child' + childNumber + 'Birthdate';
		birthdateLabel.className = 'form-label';
		birthdateLabel.innerHTML = 'Birthdate';

		let birthdateInput = document.createElement('input');
		birthdateInput.type = 'date';
		birthdateInput.className = 'form-control';
		birthdateInput.id = 'child' + childNumber + 'Birthdate';
		birthdateInput.name = 'child' + childNumber + 'Birthdate';

		let birthdateErrorMessage = document.createElement('span');
		birthdateErrorMessage.id =
			'child' + childNumber + 'BirthdateErrorMessage';
		birthdateErrorMessage.className = 'error-message text-danger';

		birthdateFieldDiv.appendChild(birthdateLabel);
		birthdateFieldDiv.appendChild(birthdateInput);
		birthdateFieldDiv.appendChild(birthdateErrorMessage);

		let childRelationshipToSoloParentFieldDiv =
			document.createElement('div');
		childRelationshipToSoloParentFieldDiv.className = 'col-lg-4 mb-3';

		let childRelationshipToSoloParentLabel =
			document.createElement('label');
		childRelationshipToSoloParentLabel.for =
			'child' + childNumber + 'RelationshipToSoloParent';
		childRelationshipToSoloParentLabel.className = 'form-label';
		childRelationshipToSoloParentLabel.innerHTML =
			'Relationship to Solo Parent';

		let childRelationshipSelection = document.createElement('select');
		childRelationshipSelection.className = 'form-select';
		childRelationshipSelection.id =
			'child' + childNumber + 'RelationshipToSoloParent';
		childRelationshipSelection.name =
			'child' + childNumber + 'RelationshipToSoloParent';

		let childRelationshipSelectionPlaceholder =
			document.createElement('option');
		childRelationshipSelectionPlaceholder.selected = true;
		childRelationshipSelectionPlaceholder.disabled = true;
		childRelationshipSelectionPlaceholder.hidden = true;
		childRelationshipSelectionPlaceholder.innerHTML = 'Select Relationship';

		let motherRelationshipOption = document.createElement('option');
		motherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent1';
		motherRelationshipOption.value = 1;
		motherRelationshipOption.innerHTML = 'Mother';

		let fatherRelationshipOption = document.createElement('option');
		fatherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent2';
		fatherRelationshipOption.value = 2;
		fatherRelationshipOption.innerHTML = 'Father';

		let siblingRelationshipOption = document.createElement('option');
		siblingRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent3';
		siblingRelationshipOption.value = 3;
		siblingRelationshipOption.innerHTML = 'Sibling';

		let stepFatherRelationshipOption = document.createElement('option');
		stepFatherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent4';
		stepFatherRelationshipOption.value = 4;
		stepFatherRelationshipOption.innerHTML = 'Step-father';

		let stepMotherRelationshipOption = document.createElement('option');
		stepMotherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent5';
		stepMotherRelationshipOption.value = 5;
		stepMotherRelationshipOption.innerHTML = 'Step-mother';

		let stepSiblingRelationshipOption = document.createElement('option');
		stepSiblingRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent6';
		stepSiblingRelationshipOption.value = 6;
		stepSiblingRelationshipOption.innerHTML = 'Step-sibling';

		let halfSiblingRelationshipOption = document.createElement('option');
		halfSiblingRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent7';
		halfSiblingRelationshipOption.value = 7;
		halfSiblingRelationshipOption.innerHTML = 'Half-sibling';

		let fosterMotherRelationshipOption = document.createElement('option');
		fosterMotherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent8';
		fosterMotherRelationshipOption.value = 8;
		fosterMotherRelationshipOption.innerHTML = 'Foster mother';

		let fosterFatherRelationshipOption = document.createElement('option');
		fosterFatherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent9';
		fosterFatherRelationshipOption.value = 9;
		fosterFatherRelationshipOption.innerHTML = 'Foster father';

		let uncleRelationshipOption = document.createElement('option');
		uncleRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent10';
		uncleRelationshipOption.value = 10;
		uncleRelationshipOption.innerHTML = 'Uncle';

		let auntRelationshipOption = document.createElement('option');
		auntRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent11';
		auntRelationshipOption.value = 11;
		auntRelationshipOption.innerHTML = 'Aunt';

		let cousinRelationshipOption = document.createElement('option');
		cousinRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent12';
		cousinRelationshipOption.value = 12;
		cousinRelationshipOption.innerHTML = 'Cousin';

		let grandFatherRelationshipOption = document.createElement('option');
		grandFatherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent13';
		grandFatherRelationshipOption.value = 13;
		grandFatherRelationshipOption.innerHTML = 'Grandfather';

		let grandMotherRelationshipOption = document.createElement('option');
		grandMotherRelationshipOption.id =
			'child' + childNumber + 'RelationshipToSoloParent14';
		grandMotherRelationshipOption.value = 14;
		grandMotherRelationshipOption.innerHTML = 'Grandmother';

		childRelationshipSelection.appendChild(
			childRelationshipSelectionPlaceholder
		);
		childRelationshipSelection.appendChild(motherRelationshipOption);
		childRelationshipSelection.appendChild(fatherRelationshipOption);
		childRelationshipSelection.appendChild(siblingRelationshipOption);
		childRelationshipSelection.appendChild(stepFatherRelationshipOption);
		childRelationshipSelection.appendChild(stepMotherRelationshipOption);
		childRelationshipSelection.appendChild(stepSiblingRelationshipOption);
		childRelationshipSelection.appendChild(halfSiblingRelationshipOption);
		childRelationshipSelection.appendChild(fosterMotherRelationshipOption);
		childRelationshipSelection.appendChild(fosterFatherRelationshipOption);
		childRelationshipSelection.appendChild(uncleRelationshipOption);
		childRelationshipSelection.appendChild(auntRelationshipOption);
		childRelationshipSelection.appendChild(cousinRelationshipOption);
		childRelationshipSelection.appendChild(grandFatherRelationshipOption);
		childRelationshipSelection.appendChild(grandMotherRelationshipOption);

		let relationshipErrorMessage = document.createElement('span');
		relationshipErrorMessage.id =
			'child' + childNumber + 'RelationshipErrorMessage';
		relationshipErrorMessage.className = 'error-message text-danger';

		childRelationshipToSoloParentFieldDiv.appendChild(
			childRelationshipToSoloParentLabel
		);

		childRelationshipToSoloParentFieldDiv.appendChild(
			childRelationshipSelection
		);

		childRelationshipToSoloParentFieldDiv.appendChild(
			relationshipErrorMessage
		);

		childsOtherInfoFieldsDiv.appendChild(childSexFieldDiv);
		childsOtherInfoFieldsDiv.appendChild(birthdateFieldDiv);
		childsOtherInfoFieldsDiv.appendChild(
			childRelationshipToSoloParentFieldDiv
		);

		childWrapperDiv.appendChild(nameFieldDiv);
		childWrapperDiv.appendChild(childsOtherInfoFieldsDiv);

		childFieldsDiv.appendChild(childWrapperDiv);
	}
}

function addNumberOfChild() {
	let numberOfChild = document.getElementById('numberOfChild');
	numberOfChild.value = parseInt(numberOfChild.value) + 1;
}
