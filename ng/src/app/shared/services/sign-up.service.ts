import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {Status} from "../interfaces/status";
import {SignUp} from "../interfaces/sign-up";

@Injectable()
export class SignUpService {

	constructor(protected http:HttpClient) {}

	private signUpUrl = "api/sign-up/";

	createUser(signUp: SignUp) : Observable<Status> {
		return(this.http.post<Status>(this.signUpUrl, signUp));
	}
}