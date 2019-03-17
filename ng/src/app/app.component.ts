import { Component } from '@angular/core';
import {SessionService} from "./shared/services/session.service";
import {Status} from "./shared/interfaces/status";

@Component({
  selector: 'app-root',
  templateUrl: `./app.component.html`,
  styles: []
})
export class AppComponent {
  status : Status = null;
constructor(private sessionService: SessionService){
  this.sessionService.setSession().subscribe(reply => this.status = reply)
}
}
