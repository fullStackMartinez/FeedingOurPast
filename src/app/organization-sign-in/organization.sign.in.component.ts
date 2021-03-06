import {Component, OnInit} from "@angular/core";
import {CookieService} from "ng2-cookies";
import {Router} from "@angular/router";
import {Status} from "../shared/classes/status";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SessionService} from "../shared/services/session.service";
import {OrganizationSignInService} from "../shared/services/organization.sign.in.service";
import {OrganizationSignIn} from "../shared/classes/organization.sign.in";
import {OrganizationSignUp} from "../shared/classes/organization.sign.up";
import {VolunteerSignInService} from "../shared/services/volunteer.sign.in.service";

//enable jquery $ alias
declare const $: any;

@Component({
	template: require("./organization.sign.in.component.html"),
	selector: "organization-sign-in"
})

export class OrganizationSignInComponent implements OnInit {

	organizationSignInForm: FormGroup;
	status: Status = null;


	constructor(
		private cookieService: CookieService,
		private sessionService: SessionService,
		private formBuilder: FormBuilder,
		private organizationSignInService: OrganizationSignInService,
		private volunteerSignInService: VolunteerSignInService,
		private router: Router) {
	}

	ngOnInit(): void {
		this.organizationSignInForm = this.formBuilder.group({
			organizationEmail: ["", [Validators.maxLength(128), Validators.required]],
			organizationPassword: ["", [Validators.maxLength(128), Validators.required]]
		});
	}

organizationSignIn() : void {

	let organization: OrganizationSignIn = new OrganizationSignIn(this.organizationSignInForm.value.organizationEmail, this.organizationSignInForm.value.organizationPassword);

	this.organizationSignInService.postOrganizationSignIn(organization)
	.subscribe(status => {
		this.status = status;
		if(this.status.status === 200) {
			this.sessionService.setSession();
			this.organizationSignInForm.reset();
			this.router.navigate(["organization"]);
			location.reload();
			console.log("sign in successful");
		} else {
			console.log("failed login");
		}
	});
}

/*signOut() : void {
	this.volunteerSignInService.getSignOut();
}*/
}