const form = document.getElementById('form');
		const fullName = document.getElementById('fullName');
		const email = document.getElementById('email');
		const password = document.getElementById('password');

		form.addEventListener('submit', (e) => {
			e.preventDefault();
			validateInputs();
		});

		function sample() {
		  alert('Hello from formValidation.js!')
		}

		function validateInputs() {
			const fullNameValue = fullName.value.trim();
			const emailValue = email.value.trim();
			const passwordValue = password.value.trim();
	
			if (fullNameValue === '' || fullNameValue === null)
			{
				setInvalidFor(fullName, 'Username cannot be blank.');
			}
			else
			{
				var checkName = /^([A-Z]){1}([a-z]){1,}$/;
				if (checkName.test(fullNameValue) == false)
				{
					setInvalidFor(fullName, 'First character must be uppercase, followed by lowercase.');
				}
				else
				{
					setValidFor(fullName);
				}
			}
			
			if (emailValue === '')
			{
				setInvalidFor(email, 'Email cannot be blank.');
			}
			else
			{
				setValidFor(email);
			}
			
			if (passwordValue === '')
			{
				setInvalidFor(password, 'Password cannot be blank.');
			}
			else if (passwordValue.length > 6)
			{
				var checkPass = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()+=-\?;,./{}|\":<>\[\]\\\' ~_]).{8,}/;
				if (checkPass.test(passwordValue) == false)
				{
					setInvalidFor(password, 'Password must contain ONE uppercase, ONE lowercase, ONE special character, numbers and no space.');
				}
				else
				{
					setValidFor(password);
				}
			}
			else
			{
				setInvalidFor(password, 'Password must be more than 6 digits.');
			}
		}

		function setInvalidFor(input, message) {
			const formControl = input.parentElement;
			const small = formControl.querySelector('small');
			
			small.innerText = message;
			
			formControl.className = 'form-control error';
			return false;
		}

		function setValidFor(input) {
			const formControl = input.parentElement;
			formControl.className = 'form-control success';
			return true;
		}