# Laravel livewire
Livewire là một full-stack framework Laravel giúp cho việc xây dựng các `dynamic interfaces` đơn giản. Nói cách khác là chúng ta có thể build 1 trang web hoàn toàn bằng Laravel mà không cần sử dụng JS.
Thực ra là nhúng các file js và css của livewire. thay vì sử dụng jquery. nhưng vẫn có thể kết hợp với js thuần hoặc jquery để xử lý một số tác vụ
## 1. Installation
Chúng ta sẽ cài đặt thông qua composer như sau :
```php
composer require livewire/livewire
```
Sau khi cài đặt thành công chỉ cần bạn `include` chúng vào nơi mà bạn cần dùng là được, ví dụ ở đây mình sẽ include vào `app.blade.php` để toàn bộ ứng dụng của mình sẽ được sử dụng livewire

`app.blade.php`
```php
 ...
  @livewireStyles
</head>
<body>
    ...

    @livewireScripts
</body>
</html>
```
Tạo 1 livewire component bằng command line:
```php artisan make:livewire TestComponent```
tự động generate ra 2 file :
```php
App\Http\Livewire\TestComponent.php

namespace App\Http\Livewire;

use Livewire\Component;

class TestComponent extends Component
{
    public function render()
    {
        return view('livewire.test-component');
    }
}

//resources\views\livewire\test-component.blade.php

<div>
   <h1>Test Component!</h1> // nội dung component đuợc đặt trong thẻ div
</div>
```
sau đó include nó vào file app.blade.php và xem kết quả :

```php
<livewire:test-component />
or
@livewire('test-component')
```
## 2. Data binding

Khái niệm đầu tiên và quan trọng nhất để hiểu khi sử dụng LiveWire là `data binding`. 
Đây là phần cốt lõi của `Livewire` nên các bạn đặc biệt chú trọng với nó. Khái niệm này khá giống với `two way data binding` ở `angular và vue`:
```php
class Message extends Component
{
   public $message = 'new message';
}
<div>
    <h1>{{ $message }}</h1>
    <!--  Output sẽ là "new message" -->
</div>
```
Data binding theo ví dụ trên có nghĩa là : Một thuộc tính `public` của Livewire component sẽ được binding qua view hoặc ngược lại, 
vậy có nghĩa là khi thuộc tính này được `change` ở view thì ở component sẽ update theo và ngược lại. Tìm hiểu ví dụ:

Tại html view ta thêm 1 ô input :
```php
<div>
	<input type="text" wire:model="message" />
        <p>{{ $message }}</p>
</div>
```
Khi gõ trên input, thì nó sẽ send request kèm input và update lại DOM, vì thế ta thấy nó sẽ realtime value.

### Có 3 chú ý như sau khi đặt tên property:

Tên property không thể conflict với tên property khác trong Livewire reserved (vd: rules hay messages).
Data sẽ được lưu dưới dạng public để có thể hiện thị phía front-end javascript, Do đó bạn không nên cho các dữ liệu nhạy cảm vào đây.
VÌ data nó sẽ được mapping với phía js cho nên những property này phải được mapping type với js (string, int, array, boolean) hoặc là với php (Stringable, Collection, DateTime, Model, EloquentCollection).
Binding Directly To Model Properties:
Ngoài ra nó còn support chúng ta binding vào model nếu khai báo nó public:
```php
use App\Post;

class PostForm extends Component
{
    public Post $post;

    protected $rules = [
        'post.title' => 'required|string|min:6',
        'post.content' => 'required|string|max:500',
    ];

    public function save()
    {
        $this->validate();

        $this->post->save();
    }
}
<form wire:submit.prevent="save">
    <input type="text" wire:model="post.title">

    <textarea wire:model="post.content"></textarea>

    <button type="submit">Save</button>
</form>
```
Như vậy khi bạn submit, mặc định các data sẽ được set vào model.

** Chú ý ** : $rules khai báo các field phải mapping với attr của model. ngoài ra nếu muốn check các rules nâng cao thì làm như sau :
```php
...
protected $rules = []
...
or
public function rules()
{
    $id = $this->post->id ?? 0;
    'post.name' => 'required|unique:posts,name,'.$id,
	// vì khi các bạn để rules trên nó sẽ ko hiểu id, và khi các bạn dùng Facade Rule cũng vậy
}
```
## 3.Actions:
Chúng như các framework FE khác thì livewire cũng có những actions tương tự để tương tác với người dùng, vd như :
```php
Event	Directive
click	wire:click
keydown	wire:keydown
submit	wire:submit
```
Nhìn khá giống với angular hoặc vue
```php
<button wire:click="doSomething">Do Something</button>

<input wire:keydown.enter="doSomething">

<form wire:submit.prevent="save">
    ...
    <button>Save</button>
</form>
```
**Bạn có thể listen cho bất kỳ event dispatched bởi element mà bạn binding**
```php
<button wire:somethingDispatcher="someAction">
```
Passing Action Parameters
Bạn cũng có thể truyền các action, param vào
```php
<button wire:click="addTodo({{ $todo->id }}, '{{ $todo->name }}')">
    Add Todo
</button>
```
Và ở component ta khai báo actions
```php
public function addTodo($id, $name)
{
    // do something
}
or
public function addTodo(Todo $todo, $name)
{
    // do something
}
or
public function addTodo(TodoService $todoService, $id, $name)
{
    // do something
}
```
Nguồn: https://laravel-livewire.com/docs/2.x/actions

## Lifecycle Hooks khá giống với các FE framework khác
```php
Hooks	  Description
mount	  Chỉ chạy 1 lần, sau khi components đc khởi tạo và trước khi render()
hydrate	  Chạy mỗi khi có request, thực hiện trước khi exec 1 action hoặc render()
updating  Chạy trước khi prop được update
updated	  Chạy sau khi prop được update
Ngoài ra còn có thể add thêm biến phía sau updating, updated ví dụ: 
hydrateFoo, updatingFoo, updatedFoo => các hooks này chạy và tương tác với biến $foo, nếu biến có 2 từ thì nhận cả 2 ví dụ : $foo_bar hoặc $fooBar.

// Chúng ta sẽ có Lifecycle Class như sau :
class TestComponent extends Component
{
    public $foo;

    public function mount() {} 
    public function hydrateFoo($value) {} 
    public function dehydrateFoo($value) {}
    public function hydrate() {}
    public function dehydrate() {}
    public function updating($name, $value) {}
    public function updated($name, $value) {}
    public function updatingFoo($value) {}
    public function updatedFoo($value) {}
    public function updatingFooBar($value) {}
    public function updatedFooBar($value) {}
}
```
Javascript Hooks
Ngoài class Lifecycle ra thì ở FE của js cũng có `Lifecycle`:
```js
<script>
    document.addEventListener("DOMContentLoaded", () => {
        Livewire.hook('component.initialized', (component) => {}) // khởi tạo
        Livewire.hook('element.initialized', (el, component) => {})  // khởi tạo
        Livewire.hook('element.updating', (fromEl, toEl, component) => {})
        Livewire.hook('element.updated', (el, component) => {})
        Livewire.hook('element.removed', (el, component) => {})
        Livewire.hook('message.sent', (message, component) => {}) /*trigger khi update message đến server thông qua Ajax*/
        Livewire.hook('message.failed', (message, component) => {}) /* Được gọi nếu message bị failed */
        Livewire.hook('message.received', (message, component) => {})/* Được gọi nếu nhận response, và trước khi update DOM */
        Livewire.hook('message.processed', (message, component) => {}) /* Được gọi trước khi Livewire processes all side effects (including DOM-diffing) từ message */
    });
</script>
```
message là mỗi khi ta tương tác đến phía component nó sẽ call ajax để update lại DOM, cả lượt đi lẫn lượt về gọi là roundtrip.

Nguồn: https://laravel-livewire.com/docs/2.x/lifecycle-hooks

## Nesting Components
`Nested components` có thể nhận data parameters từ parent của nó, Tuy nhiên chúng không reactive như props từ `vue component`
Livewire component không nên được dùng cho các trường hợp tách nhỏ như blade files, đối với như này, chúng ta cứ dùng `include` của blade thì tốt hơn:
```php
class UserDashboard extends Component
{
    public User $user;
}
<div>
    <h2>User Details:</h2>
    Name: {{ $user->name }}
    Email: {{ $user->email }}

    <h2>User Notes:</h2>
    <div>
       <!-- nested component -->
        @livewire('add-user-note', ['user' => $user])
    </div>
</div>
```
Nguồn: https://laravel-livewire.com/docs/2.x/nesting-components

## Events
Một phần rất quan trọng của livewire, cũng như các framework, library khác. Các livewire component có thể giao tiếp với nhau thông qua `global event`, miễn chúng đang trên cùng 1 trang, chúng có thể giao tiếp bằng các `events and listeners`.

#### Firing Events
Có 3 cách để `firing events`.
```php
<!-- từ template -->
<button wire:click="$emit('postAdded')">
// từ component
$this->emit('postAdded');
/* từ global js */
<script>
   Livewire.emit('postAdded')
</script>
```
Thì sau khi chúng ta fire một event thì chắc sẽ có listener để nghe nó, và sẽ được define trong component như sau :
```php
class ShowPosts extends Component
{
    public $postCount;

    protected $listeners = ['postAdded' => 'postAddedAction'];
   // vậy mỗi lần postAdded được fire thì action postAddedAction sẽ được gọi
   // nếu cả listener và action cùng tên, ta chỉ cần protected $listeners = ['postAdded'];
    public function postAddedAction()
    {
        // handle something
    }
	
	// hoặc nếu bạn muốn event được dynamic thì khai báo
    protected function getListeners()
    {
        return ['postAdded' => true ? 'postAddedAction' : 'someAction'];
    }
}
```
Passing Parameters
Chỉ cần 1 ví dụ là hiểu được ngay , ngắn gọn xúc tích.
```php
$this->emit('postAdded', $post->id);


 // another component
public function postAdded(Post $post)
{
	$this->postCount = Post::count();
	$this->recentlyAddedPost = $post;
}
```
Nguồn: https://laravel-livewire.com/docs/2.x/events

## Scoping Events
--- Scoping To Parent Listeners
Khi làm việc với nested components, thỉnh thoảng bạn chỉ muốn emit events đến parents và không emit tới `children` hoặc `sibling` components.
```php
$this->emitUp('postAdded');
hoặc

<button wire:click="$emitUp('postAdded')">
--- Scoping To Components By Name
Hoặc emit đến tên component chỉ định

$this->emitTo('counter', 'postAdded');
<button wire:click="$emitTo('counter', 'postAdded')">
--- Scoping To Self
Hoặc chỉ emit chỉnh component này thôi (thường chỉ cách này ta call trực tiếp đến action đó khoẻ hơn :v )

$this->emitSelft('counter', 'postAdded');
<button wire:click="$emitSelft('counter', 'postAdded')">
Ngoài listen ở component ta cũng có thể listen ở js:
Thường chúng ta dùng cái này để show các toarts message, …

<script>
Livewire.on('postAdded', postId => {
   alert('A post was added with the id of: ' + postId);
})
</script>
```
## Dispatching Browser Events
Livewire cho phép bạn fire browser window event như sau:
```php
$this->dispatchBrowserEvent('name-updated', ['newName' => $value]);
```
Và bạn sẽ listen nó như sau:
```js
<script>
window.addEventListener('name-updated', event => {
    alert('Name updated to: ' + event.detail.newName);
})
</script>
```
