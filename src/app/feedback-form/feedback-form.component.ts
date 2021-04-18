import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, retry } from 'rxjs/operators';

function nameValidator(control: FormControl): {[key: string]: any} {
  const value: string = control.value || '';
  const valid = value.match(/^\D*$/);
  return valid ? null : {error: ''};
}

@Component({
  selector: 'app-feedback-form',
  templateUrl: './feedback-form.component.html',
  styleUrls: ['./feedback-form.component.scss']
})

export class FeedbackFormComponent implements OnInit {

  public formFeedback: FormGroup;
  public preloadFlag: boolean;
  public messageFlag: boolean;
  public messageTimeOut: number;
  public message: string;

  constructor(private http:HttpClient) {
    this.preloadFlag = false;
    this.messageFlag = false;
    this.messageTimeOut = 3000;
  }

  ngOnInit(): void {
    this.formFeedback = new FormGroup({
      name: new FormControl('', [Validators.required, nameValidator]),
      email: new FormControl('',[Validators.required, Validators.email]),
      phone: new FormControl('',[Validators.required]),
      text: new FormControl('', [Validators.required, Validators.minLength(10)])
    });
    
  }

  public onSubmit(){
    if(this.formFeedback.status == 'VALID'){
      this.preloadFlag = true;
      const url = '/';
      this.http.post(url,{
        name: this.formFeedback.value['name'],
        email: this.formFeedback.value['email'],
        phone: this.formFeedback.value['phone'],
        text: this.formFeedback.value['text']
      }).subscribe((result) => {
          this.preloadFlag = false;
          this.messageFlag = true;
          this.message = 'Сообщение доставлено успешно.';
          setTimeout(this.hideMessage.bind(this), this.messageTimeOut);
      }, (error) => {
          this.preloadFlag = false;
          this.messageFlag = true;
          this.message = 'Ошибка при отправке.';
          setTimeout(this.hideMessage.bind(this), this.messageTimeOut);
      });
    }else{
      for (const field in this.formFeedback.controls)
      { 
        this.formFeedback.controls[field].markAsTouched();
      }

    }
  }

  private hideMessage(){
    console.log(this.messageFlag);
    if(this.messageFlag === true){
      
      this.messageFlag = false;
      this.message = '';
    }
  }
}
