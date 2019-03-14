import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {Status} from "../interfaces/status";
import {User} from "../interfaces/user";

@Injectable()
export class SignUpService {

	constructor(protected http:HttpClient) {}

	private signUpUrl = "apis/sign-up/";

	postUser(user: User) : Observable<Status> {
		return(this.http.post<Status>(this.signUpUrl, user));
	}
}