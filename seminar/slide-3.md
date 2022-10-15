### Query String
Với livewire chúng ta sẽ dễ dàng search không lo load lại trang và live query string trên `url` một cách dễ dàng:
```php
class SearchPosts extends Component
{
    public $search;

    protected $queryString = ['search'];

    public function render()
    {
        return view('livewire.search-posts', [
            'posts' => Post::where('title', 'like', '%'.$this->search.'%')->get(),
        ]);
    }
}
<div>
    <input wire:model.lazy="search" type="search" placeholder="Search posts by title...">

    <h1>Search Results:</h1>

    <ul>
        @foreach($posts as $post)
            <li>{{ $post->title }}</li>
        @endforeach
    </ul>
</div>
```
#### Giải thích :
Đầu tiên bạn sẽ khai báo property muốn search
Tiếp theo, bạn khai báo biến đó vào mảng `protected $queryString`
Và dùng biến đó như bình thường để query vào DB
Nó sẽ update search khi mà người dùng nhập vào input vì chúng ta đang binding model vào input.

#### Vấn đề xảy ra
Nếu bạn lo sợ người dùng nhập liên tục khi nó sẽ request liền tục thi bạn cứ thêm vào `Debouncing Input, lazy, defer … `của phần binding input.
Tham khảo tại đây: https://laravel-livewire.com/docs/2.x/properties#debouncing-input

Keeping A Clean Query String
Trong nhiều trường hợp, ví dụ bạn keyword search của bạn empty thì nó sẽ như này : ?search= Tuy nhiên livewire lại không hiển như vậy.
```php
class SearchPosts extends Component
{
    public $foo;
    public $search = '';
    public $page = 1;

    protected $queryString = [
        'foo',
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    ...
}
```
Khi bạn khai báo giá trị search vào `queryString` là mảng và trong mảng là phần tử except, như vậy nó sẽ loại trừ những cái mà bạn khai báo đó.ví dụ ở đây khi search empty hoặc page = 1, thì nó sẽ không xuất hiện trên url, quá dễ dàng cho phần query string của livewire.
Nguồn: https://laravel-livewire.com/docs/2.x/query-string

### Authorization
Ở laravel, khi ta muốn phân quyền cho 1 actions nào đó, ta sẽ dùng authorize trong method đó, và livewire cũng đc, bạn có thể sử dụng trait `AuthorizesRequests` trong bất kì component nào, và `call $this->authorize()` như bạn call ở controller.
```php
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EditPost extends \Livewire\Component
{
    use AuthorizesRequests;

    public $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function save()
    {
        $this->authorize('update', $this->post);

        $this->post->update(['title' => $this->title]);
    }
}
```
Cách sử dụng giống trong controller.
```php
...
'middleware_group' => ['web', 'auth:otherguard'],
...
```
Nhưng thông thường ta sẽ config ở route, cách này thường hiếm sử dụng. Khá là đơn giản khi làm việc với Authorization.
Nguồn: https://laravel-livewire.com/docs/2.x/authorization

### Pagination
Livewire cũng cung cấp phân trang cho component. Tính năng này kết hợp với các tính năng phân trang gốc của Laravel, 
vì vậy nó sẽ giống như một tính năng bình thường đối với bạn, nhưng lại không cần load lại trang.

#### Paginating Data
Bây giờ bạn hãy tạo 1 component là `show post` để thực hiện phân trang, bạn có thể sử dụng phân trang như cách bình thường 
Nếu bạn sử dụng `trait WithPagination`, nó sẽ giúp bạn phân trang mượt mà, linh động và không cần load lại trang.
```php
use Livewire\WithPagination;

class ShowPosts extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.show-posts', [
            'posts' => Post::paginate(10),
        ]);
    }
}
<div>
    @foreach ($posts as $post)
        ...
    @endforeach

    {{ $posts->links() }}
</div>
```
`use WithPagination;` và cú pháp giống `Laravel`

#### Resetting Pagination After Filtering Data
Đôi lúc, lúc bạn đang ở trang 5, mà giờ bạn search 1 thứ gì đó có có 1 kết quả thì bạn đáng ra phải ở trang 1, 
nếu không bạn ở mãi trang 5 và không có data gì, vì vậy livewire sẽ cung cấp cho ta 1 cách là mỗi lần bạn apply filter, 
sẽ giúp bạn tự động quay về trang đầu, để được cải thiện UX hơn:
```php
use Livewire\WithPagination;

class ShowPosts extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
	
...
}
```
Với `WithPagination` bạn có thể sử dụng method `resetPage()`, nó sẽ giúp bạn reset lại trang đầu tiên. 
Ở đây chúng ta sử dụng `UpdatingSearch`, một lifecycle hooks được trigger khi props `$search` của chúng ta đang update.

#### Using The Bootstrap Pagination Theme
Như laravel, phân trang sẽ sử dụng theme mặc định, đối với `livewire` nó sẽ mặc định là dùng `tailwind css` làm theme mặc định, nếu bạn muốn sử dụng `bootstrap` làm theme, chỉ cần khai báo :
```php
class ShowPosts extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    // lúc này, pagination của bạn sẽ apply bootstrap.
```
#### Using A Custom Pagination View
Khi dùng pagination và dùng mặc định, Đa số mọi người sẽ custom lại và livewire cũng hoạt động tương tự laravel, bạn có thể custom tuỳ thích.

Có 3 cách để bạn custom pagination:

**Pass view name vào ->links() ở component**
```php
<div>
    @foreach ($posts as $post)
        ...
    @endforeach

    {{ $posts->links('custom-pagination-links-view') }}
</div>
```
**Ghi đè lại method paginationView() của trait WithPagination:**
```php
class ShowPosts extends Component
{
    use WithPagination;

    ...

    public function paginationView()
    {
        return 'custom-pagination-links-view';
    }

    ...
}
```
**Publish view ra và custom trong đó:** `php artisan livewire:publish --pagination`
```php
<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
            <span>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                        {!! __('pagination.previous') !!}
                    </button>
                @endif
            </span>

            <span>
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                        {!! __('pagination.next') !!}
                    </button>
                @else
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </span>
        </nav>
    @endif
</div>
```
Như laravel thông thường, nó sẽ dùng thẻ <a> chuyển trang, nhưng bạn thấy `wire:click="previousPage"`, wire:click="nextPage" được sử dụng ở đây, cái này nằm ở trong `trait WithPagination` và nó sẽ giúp ta re-render component và không cần load lại. Ngoài ra có nhiều method khác như :
```php
<?php
...
trait WithPagination
{
    public $page = 1;
    public function previousPage()
    {
        $this->setPage(max($this->page - 1, 1));
    }
    public function nextPage()
    {
        $this->setPage($this->page + 1);
    }
    public function gotoPage($page)
    {
        $this->setPage($page);
    }
    public function resetPage()
    {
        $this->setPage(1);
    }
    public function setPage($page)
    {
        $this->page = $page;
    }
    public function resolvePage()
    {
        // The "page" query string item should only be available
        // from within the original component mount run.
        return (int) request()->query('page', $this->page);
    }
}
```
Khá là đẩy đủ cho bạn để custom page theo ý muốn .
Nguồn: https://laravel-livewire.com/docs/2.x/pagination

### Redirecting
Bạn có thể muốn chuyển hướng từ bên trong một thành phần Livewire đến một trang khác trong ứng dụng của mình. Livewire hỗ trợ cú pháp phản hồi chuyển hướng tiêu chuẩn mà bạn đã quen sử dụng trong Controller Laravel.
```php
class ContactForm extends Component
{
    public $email;

    public function addContact()
    {
        Contact::create(['email' => $this->email]);

        return redirect()->to('/contact-form-success');
    }
}
```
Livewire sử dụng bộ chuyển hướng của laravel, nên bạn cũng có thể sử dụng những cái khác như : 
`redirect('/foo'), redirect()->to('/foo'), redirect()->route('foo')`

### Flash Messages
Flash message của livewire cũng sử dụng lại của laravel, nên bạn cứ sử dụng 1 cách bth như lâu nay sử udnjg :D
```php
public function update()
{
	$this->validate();
	$this->post->save();
	session()->flash('message', 'Post successfully updated.');
}
@if (session()->has('message'))
	<div class="alert alert-success">
		{{ session('message') }}
	</div>
@endif
```
Bạn cũng có thể sử flash và redirect luôn, như bạn làm thông thường với laravel
```php
session()->flash('message', 'Post successfully updated.');

return redirect()->to('/posts');
```
Nguồn: https://laravel-livewire.com/docs/2.x/redirecting

### Traits
Cái này chắc không con xa lạ gì khi bạn làm việc với php, nhưng đối với livewire giống `mixin` trong `vuejs`, và có thể sử dụng lại các method ở nhiều component. Ở đây chúng ta tạo 1 trait đơn giản:
```php
trait WithSorting
{
    public $sortBy = '';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortBy === $field
            ? $this->reverseSort()
            : 'asc';

        $this->sortBy = $field;
    }

    public function reverseSort()
    {
        return $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
    }
}
// use trait
class ShowPosts extends Component
{
	use WithSorting; // use vào và sử dụng thôi

    public $sortBy = '';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortBy === $field
            ? $this->reverseSort()
            : 'asc';

        $this->sortBy = $field;
    }

    public function reverseSort()
    {
        return $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
    }

    ...
}
```
Ngoài ra, cũng có thể áp dụng `lifecycle hook` vào :
```php
trait WithSorting
{
    ...
    public function mountWithSorting(){}
    public function updatingWithSorting($name, $value){}
    public function updatedWithSorting($name, $value){}
    public function hydrateWithSorting(){}
    public function dehydrateWithSorting(){}
    public function renderingWithSorting(){}
    public function renderedWithSorting($view){}
}
```
Nguồn: https://laravel-livewire.com/docs/2.x/traits
