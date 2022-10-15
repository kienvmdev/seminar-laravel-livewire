## Form validation
### 1. Intro
Livewire sẽ cung cấp cho chúng ta `property $rules` cho việc setting validation trong mỗi form. và method `$this->validate()` sẽ tiến hành validate những property được xác định trong form.
```php
class LoginForm extends Component
{
    public $username;
    public $password;

    protected $rules = [
        'username' => 'required|min:6',
        'password' => 'required|min:6',
    ];

    public function submit()
    {
        // kiểm tra validate
        $this->validate();

        // nếu validate thất bại, nó sẽ không pass được tới đây
	Auth::attempt([
            'username' => $this->username,
            'password' => $this->password,
        ]);
    }
}
<form wire:submit.prevent="submit">
    <input type="text" wire:model="username">
    @error('username') <span class="error">{{ $message }}</span> @enderror

    <input type="password" wire:model="password">
    @error('password') <span class="error">{{ $message }}</span> @enderror

    <button type="submit">Login</button>
</form>
```
Chúng ta có sử dụng `wire:submit.prevent, wire:model`

**NOTE**: Nếu validation thất bại thì `ValidationException` sẽ được bắn ra và được livewire bắt, và `$errors object` sẽ có sẵn ở `view component`. Do vậy bạn có thể hoàn toàn xử lý chúng bao bao gồm cả blade mà bạn included.

Ngoài ra bạn cũng có thể custom `key/message` trong error bag
```php
$this->addError('key', 'message')
```
Nếu bạn cần định phía rules dynamically , bạn có thể thay thế $rules property bằng method `rules()` ở component, vì `property $rules` chỉ nhận những rule cơ bản, với những case phức tạp, bắt buộc bạn phải dùng tới method `rules()` này:
```php
use Rule;

class Login extends Component
{
    public $username;
    public $password;

    protected function rules()
    {
        return [
            'username' => [
				'required',
				Rule::exists('users', 'username'),
				... 
			],
            'password' =>'required',
        ];
    }
}
```
### 2. Real-time Validation
Đôi lúc bạn muốn ô input nào đó sẽ được validate tức khắc khi người dùng nhập đó, Livewire có thể làm được nó 1 cách dễ dàng với phương thức `$this->validateOnly()`: quay về form ban nãy, chúng ta sẽ thêm method `updated()`, cái này là 1 `Lifecycle hooks` của livewire
```php
class Login extends Component
{
    public $username;
    public $password;

    protected $rules = [
        'username' => 'required|min:6',
        'password' => 'password|min:6',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
...
}
```
Khi người dụng nhập `username` chưa đủ 6 chữ, lúc này `message validate` của `min:6` sẽ show lên

Do property username được gắn vào input là `wire:model` nên khi type sẽ `trigger updated` (Lifecycle hooks)
Updated sẽ nhận vào 1 tham số đó chính là pros nào được update, cứ props nào được change thì nó sẽ được gọi là `re-render component`
Sau đó chúng ta sẽ tiến hành field đó với `$this->validateOnly($propertyName);` , khi fail, nó sẽ re-render và kèm với msg đúng với field đó
Vậy tại sao không dùng `$this->validated()` mà lại dùng `$this->validateOnly($propertyName);`, thật ra dùng cũng được nhưng `validated()` sẽ validate toàn bộ.

Validating with rules outside of the `$rules property`
Thật ra cách này thì cũng như những validate trên, nhưng thay vì ta khai báo `$rules` thì t sẽ khi báo mảng đó ở :
```php
$this->validateOnly($propertyName, [
	'username' => 'min:6',
	'password' => 'min:6',
]);

...
hoặc 
$validatedData = $this->validate([
	'username' => 'min:6',
	'password' => 'min:6',
]);
```
Nguồn: https://laravel-livewire.com/docs/2.x/input-validation

### 3. Customize Error Message & Attributes
Request của Laravel :
```php
   ...
   public function rules()
   {
       return [
   		'email' => 'required|email',
   	];
   }
   
   public function message()
   {
       return [
   		'email.required' => 'The Email Address cannot be empty.',
   		'email.email' => 'The Email Address format is not valid.',
   	];
   }
   
   public function attributes()
   {
       return [
   		'email' => 'email address'
   	];
   }
```
Trong laravel livewire
```php
public $email;

protected $rules = [
	'email' => 'required|email',
];

protected $messages = [
	'email.required' => 'The Email Address cannot be empty.',
	'email.email' => 'The Email Address format is not valid.',
];

protected $validationAttributes = [
	'email' => 'email address'
];

// hoặc có thể gộp 3 cái vào 1:
$validatedData = $this->validate(
	['email' => 'required|email'],
	[
		'email.required' => 'The :attribute cannot be empty.',
		'email.email' => 'The :attribute format is not valid.',
	],
	['email' => 'Email Address']
);
```
### 4. Direct Error Message Manipulation
2 method `validate() and validateOnly()` sẽ handle trong mọi case, nhưng thỉnh thoảng bạn có thể control lại Livewire’s ErrorBag, kiểu kiểm soát lại việc show lỗi.

Livewire cung cấp một số phương pháp để bạn thao tác trực tiếp với ErrorBag.
```php
// Cách nhanh nhất để thêm message vào error bag
$this->addError('email', 'The email field is invalid.');

// 2 method dùng để clear error bah
$this->resetErrorBag();
$this->resetValidation();

// ngoài ra, bạn có thể chỉ định field muốn clear
$this->resetValidation('email');
$this->resetErrorBag('email');

// lấy full error
$errors = $this->getErrorBag();
// với instance error bag, bạn cũng có thể làm như này :
$errors->add('some-key', 'Some message');
```
### 5. Custom validators
Ngoài ra bạn có thể dùng `use Illuminate\Support\Facades\Validator;` để custom lại theo ý bạn muốn
```php
$validatedData = Validator::make(
	['email' => $this->email],
	['email' => 'required|email'],
	['required' => 'The :attribute field is required'],
)->validate();
<div>
    Email: <input wire:model.lazy="email">

    @if($errors->has('email'))
        <span>{{ $errors->first('email') }}</span>
    @endif

    <button wire:click="saveContact">Save Contact</button>
</div>
```
### Ngoài ra còn có xử lý upload file hoặc multi upload file
Nguồn: https://laravel-livewire.com/docs/2.x/file-uploads
