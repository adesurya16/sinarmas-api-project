---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#general
<!-- START_d7b7952e7fdddc07c978c9bdaf757acf -->
## Register
Desc Function : Function untuk register user

> Example request:

```bash
curl -X POST "http://localhost/api/register" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/register",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/register`


<!-- END_d7b7952e7fdddc07c978c9bdaf757acf -->

<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## Login
Desc Function : Function untuk user login jika sudah register

> Example request:

```bash
curl -X POST "http://localhost/api/login" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/login",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/login`


<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

<!-- START_499f5d8c2edb76351e75311c861ce793 -->
## Detail Profile
Desc Function : Menampilkan detail profile user

> Example request:

```bash
curl -X GET "http://localhost/api/profile/{profile_id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/profile/{profile_id}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/profile/{profile_id}`

`HEAD api/profile/{profile_id}`


<!-- END_499f5d8c2edb76351e75311c861ce793 -->

<!-- START_a8345e0ee8e848e39510b1190c00f567 -->
## All Activities
Desc Function : Menampilkan activities user

> Example request:

```bash
curl -X GET "http://localhost/api/posting" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/posting",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": 0,
    "message": "Tidak ada data",
    "total_rows": 0,
    "data": []
}
```

### HTTP Request
`GET api/posting`

`HEAD api/posting`


<!-- END_a8345e0ee8e848e39510b1190c00f567 -->

<!-- START_8987a6b7d3ba90accfff4a5c5bbcf3c5 -->
## Detail Activities
Desc Function : Menampilkan detail activities user

> Example request:

```bash
curl -X GET "http://localhost/api/posting/{posting_id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/posting/{posting_id}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/posting/{posting_id}`

`HEAD api/posting/{posting_id}`


<!-- END_8987a6b7d3ba90accfff4a5c5bbcf3c5 -->

<!-- START_c52031bded951431541f701a7fc0e176 -->
## Set Verified Submissions User
Desc Function : Function ini untuk mengubah pengajuan user yang terverifikasi diterima atau ditolak

> Example request:

```bash
curl -X PUT "http://localhost/api/set-verified/{id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/set-verified/{id}",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/set-verified/{id}`


<!-- END_c52031bded951431541f701a7fc0e176 -->

<!-- START_c50828260aa53a83af3fd663072ba1f7 -->
## Posting Activities
Desc Function : Posting Activities dari user yang telah login

> Example request:

```bash
curl -X POST "http://localhost/api/posting" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/posting",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/posting`


<!-- END_c50828260aa53a83af3fd663072ba1f7 -->

<!-- START_a077aac73871adc509d6c0bd1d010002 -->
## My Update
Desc Function : Mengganti informasi profile user yang sedang login

> Example request:

```bash
curl -X PUT "http://localhost/api/myprofile" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/myprofile",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/myprofile`


<!-- END_a077aac73871adc509d6c0bd1d010002 -->

<!-- START_dec6783705d5a5c19cfc9341764d0e7b -->
## My Profile
Desc Function : Menampilkan informasi profile yang sedang login

> Example request:

```bash
curl -X GET "http://localhost/api/myprofile" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/myprofile",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "token_not_provided"
}
```

### HTTP Request
`GET api/myprofile`

`HEAD api/myprofile`


<!-- END_dec6783705d5a5c19cfc9341764d0e7b -->

<!-- START_0f5e59e5d39a318daed6631442199c5d -->
## All Events
Desc Function : Function ini untuk mengambil semua / filter data events

> Example request:

```bash
curl -X GET "http://localhost/api/events" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/events",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "token_not_provided"
}
```

### HTTP Request
`GET api/events`

`HEAD api/events`


<!-- END_0f5e59e5d39a318daed6631442199c5d -->

<!-- START_2f937339643bb255988a5ee41f56547c -->
## Detail Events
Desc Function : Function ini untuk mengambil spesifik data events

> Example request:

```bash
curl -X GET "http://localhost/api/events/{id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/events/{id}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "token_not_provided"
}
```

### HTTP Request
`GET api/events/{id}`

`HEAD api/events/{id}`


<!-- END_2f937339643bb255988a5ee41f56547c -->

<!-- START_de3413bf02c9bb71627fa96e1c1c409f -->
## Create Events
Desc Function : Function ini untuk membuat events

> Example request:

```bash
curl -X POST "http://localhost/api/events" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/events",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/events`


<!-- END_de3413bf02c9bb71627fa96e1c1c409f -->

<!-- START_ce40b64f26c58e8158315bc19dfeb559 -->
## Approved Events
Desc Function : Function ini untuk event yang diapproved oleh admin

> Example request:

```bash
curl -X PUT "http://localhost/api/events/{id}/approved" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/events/{id}/approved",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/events/{id}/approved`


<!-- END_ce40b64f26c58e8158315bc19dfeb559 -->

<!-- START_9adbacfb77d018908aac1c00cc0ab350 -->
## Attends Events
Desc Function : Function ini untuk user yang ingin menghadiri event

> Example request:

```bash
curl -X PUT "http://localhost/api/events/{id}/attend" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/events/{id}/attend",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/events/{id}/attend`


<!-- END_9adbacfb77d018908aac1c00cc0ab350 -->

<!-- START_b490236236e9e88216010cb09d36a35f -->
## All Trees
Desc Function : Menampilkan semua data pohon

> Example request:

```bash
curl -X GET "http://localhost/api/trees" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/trees",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "token_not_provided"
}
```

### HTTP Request
`GET api/trees`

`HEAD api/trees`


<!-- END_b490236236e9e88216010cb09d36a35f -->

<!-- START_fbedaa66bfa1963ea6bf4e987fb7a00e -->
## All Submissions User
Desc Function : Function ini untuk mengambil semua data permohonan verifikasi akun

> Example request:

```bash
curl -X GET "http://localhost/api/submission_verified" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/submission_verified",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "token_not_provided"
}
```

### HTTP Request
`GET api/submission_verified`

`HEAD api/submission_verified`


<!-- END_fbedaa66bfa1963ea6bf4e987fb7a00e -->

<!-- START_d54b56d8c5de6ba33d7daf908978b13b -->
## Insert Submissions User
Desc Function : Function ini untuk menyimpan data permintaan untuk menjadi user yang terverifikasi

> Example request:

```bash
curl -X POST "http://localhost/api/submission_verified" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/submission_verified",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/submission_verified`


<!-- END_d54b56d8c5de6ba33d7daf908978b13b -->

<!-- START_399d36d3aea1593e0bf5f85efc1ec7ab -->
## Re-submission Submissions User
Desc Function : Function ini untuk mengubah pengajuan user yang direject kembali ke 0 agar bisa mengajukan kembali

> Example request:

```bash
curl -X PUT "http://localhost/api/re-submission" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/re-submission",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/re-submission`


<!-- END_399d36d3aea1593e0bf5f85efc1ec7ab -->

<!-- START_fe20e15f33706475b15f1d92d73568dc -->
## All Sponsor
Desc Function : Function ini untuk mengambil semua data sponsor

> Example request:

```bash
curl -X GET "http://localhost/api/sponsor" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/sponsor",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "token_not_provided"
}
```

### HTTP Request
`GET api/sponsor`

`HEAD api/sponsor`


<!-- END_fe20e15f33706475b15f1d92d73568dc -->

