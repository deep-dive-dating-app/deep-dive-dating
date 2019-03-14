import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Post} from "../classes/post";
import {Status} from "../classes/status";
import {Observable} from "rxjs";

@Injectable()
export class UserDetailService {
	constructor(protected http: HttpClient) {
	}

	private userDetailUrl = "apis/user-detail/";

	editUserDetail(userDetail: UserDetail) : Observable<Status> {
		return(this.http.put<Status>(this.http.userDetailUrl + userDetail.userDetailId, userDetail));
	}
}