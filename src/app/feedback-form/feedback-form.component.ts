import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { HttpClient } from '@angular/common/http';

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

  formFeedback: FormGroup;

  constructor(private http:HttpClient) {
    
  }

  ngOnInit(): void {
    this.formFeedback = new FormGroup({
      name: new FormControl('', [Validators.required, nameValidator]),
      email: new FormControl('',[Validators.required, Validators.email]),
      phone: new FormControl('',[Validators.required]),
      text: new FormControl('', [Validators.required, Validators.minLength(10)])
    });
  }

  onSubmit(){
    if(this.formFeedback.status == 'VALID'){
      console.log('valid');

      const url = '/';
      this.http.post(url,{
        name: this.formFeedback.value['name'],
        email: this.formFeedback.value['email'],
        phone: this.formFeedback.value['phone'],
        text: this.formFeedback.value['text']
      }).subscribe((result) => console.warn(result));

    }else{
      for (const field in this.formFeedback.controls)
      { 
        this.formFeedback.controls[field].markAsTouched();
      }

    }
  }
}
