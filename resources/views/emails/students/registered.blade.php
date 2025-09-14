@component('mail::message')
# 登録完了のお知らせ

{{ $student->last_name }} {{ $student->first_name }} 様

この度はご登録いただきありがとうございます。  
以下の内容で生徒情報が登録されました。

- 氏名: {{ $student->last_name }} {{ $student->first_name }}
- 学校名: {{ $student->school_name }}
- 学年: {{ $student->grade }}

今後ともよろしくお願いいたします。

@endcomponent
