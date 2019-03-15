import {RouterModule, Routes} from "@angular/router";
import {SplashComponent} from "./splash/splash.component";
import{AppComponent} from "./app.component";
import {SignInService} from "./shared/services/sign-in.service";
import {SignInComponent} from "./shared/components/sign-in/sign-in.component";
import {SessionService} from "./shared/services/session.service";
import {SignUpComponent} from "./sign-up/sign-up.component";

import {APP_BASE_HREF} from "@angular/common";


export const allAppComponents = [AppComponent, SplashComponent, SignInComponent, SignUpComponent];

export const routes: Routes = [
	{path: "", component: SplashComponent},
	{path: "sign-in", component: SignInComponent},
	{path: "sign-up", component: SignUpComponent}
];

export const appRoutingProviders: any[] = [
	SessionService,
	SignInService,
	SignUpComponent
];

export const routing = RouterModule.forRoot(routes);