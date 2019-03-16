import { NgModule,  } from '@angular/core';
import {HttpClientModule} from "@angular/common/http";
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import {ReactiveFormsModule} from "@angular/forms";
import {allAppComponents, appRoutingProviders, routing} from "./app.routes"
import { AppComponent } from './app.component';
import {JwtModule} from "@auth0/angular-jwt";


const JwtHelper = JwtModule.forRoot({
  config : {
    tokenGetter: () => {
      return localStorage.getItem("jwt-token");
    },
    skipWhenExpired: true,
    whitelistedDomains: ["localhost:7272", "https:bootcamp-coders.cnm.edu/"],
    headerName: "X-JWT-TOKEN",
    authScheme: ""
  }
});

const moduleDeclarations = [AppComponent];

@NgModule({
  imports:      [BrowserModule, HttpClientModule, ReactiveFormsModule, FormsModule, routing],
  declarations: [...moduleDeclarations, ...allAppComponents],
  bootstrap:    [AppComponent],
  providers:    [appRoutingProviders]
})
export class AppModule {}
