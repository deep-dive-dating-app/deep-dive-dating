import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {User} from "../interfaces/user";
import {UserDetail} from "../interfaces/user-detail";
import {UserWithUserDetail} from "../interfaces/userWithUserDetail";
import {Status} from "../interfaces/status";
import {Observable} from "rxjs";

@Injectable()
export class UserWithUserDetailService {
	constructor(protected http: HttpClient) {
	}

	private userDetailUrl = "api/userDetail/";

	private userUrl = "api/user/";

	getUserByUserId(UserId) : Observable<User> {
		return(this.http.get<User>(this.userUrl + "?userUserId=" + UserId));
	}

	getUserDetailByUserId(UserId) : Observable<UserDetail> {
		return(this.http.get<UserDetail>(this.userDetailUrl + "?userDetailUserId=" + UserId));
	}

	getUserWithUserDetial(UserId) {
		this.getUserByUserId(UserId);
		this.getUserDetailByUserId(UserId);
		return
	}
}