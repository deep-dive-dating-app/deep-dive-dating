import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {UserDetail} from "../interfaces/user-detail";
import {Status} from "../interfaces/status";
import {Observable} from "rxjs";

@Injectable()
export class UserDetailService {
	constructor(protected http: HttpClient) {
	}

	private userDetailUrl = "api/userDetail/";

	editUserDetail(userDetail: UserDetail) : Observable<Status> {
		return(this.http.put<Status>(this.userDetailUrl + userDetail.userDetailId, userDetail));
	}

	getUserDetailByUserId(UserId) : Observable<UserDetail> {
		return(this.http.get<UserDetail>(this.userDetailUrl + "?userDetailUserId=" + UserId));
	}
}