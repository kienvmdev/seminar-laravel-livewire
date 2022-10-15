<div class="col-12">
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class='task-error alert alert-danger mt-3 mb-4' style='display:none'></div>
    <div class='task-success alert alert-success mt-3 mb-4' style='display:none'></div>
    <form>
        <div class="form-group">
            <label for="title" class="font-weight-bold">Title: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" placeholder="Enter Title" wire:model="title">
            @error('title') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="title" class="font-weight-bold">Slug: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" placeholder="Enter Slug" wire:model="slug">
            @error('slug') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="content" class="font-weight-bold">Content: <span class="text-danger">*</span></label>
            <textarea type="email" class="form-control" id="content" wire:model="content"
                      placeholder="Enter Content" rows="6"></textarea>
            @error('content') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="title" class="font-weight-bold">Category: <span class="text-danger">*</span></label>
            <select class="custom-select mb-2 select2" id="category_id" wire:model="category_id">
                <option value="" selected>---Category---</option>
                @foreach($categories as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-danger error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="title" class="font-weight-bold">Tags:</label>
            <div wire:ignore>
                <select wire:model="tag_ids" id="tag_ids" class="form-control select2" multiple>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" @if(in_array($tag->id, $tag_ids)) selected @endif>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('tag_ids') <span class="text-danger error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status0" wire:model="status"
                       value="0"
                       @if($status == 0) checked @endif>
                <label class="form-check-label" for="status0">Draf</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status1" wire:model="status"
                       value="1"
                       @if($status == 1) checked @endif>
                <label class="form-check-label" for="status1">Resolved</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status2" wire:model="status"
                       value="2"
                       @if($status == 2) checked @endif>
                <label class="form-check-label" for="status2">Verified</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status3" wire:model="status"
                       value="3"
                       @if($status == 3) checked @endif>
                <label class="form-check-label" for="status3">Private</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status4" wire:model="status"
                       value="4"
                       @if($status == 4) checked @endif>
                <label class="form-check-label" for="status4">Published</label>
            </div>
            @error('status') <span class="text-danger error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <button wire:click.prevent="resetFields()" class="btn btn-danger">Reset</button>
            <button wire:click.prevent="store()" class="btn btn-success">Save</button>
        </div>
    </form>
    @if(count($posts) > 0)
        <table class="table table-bordered mt-5">
            <thead>
            <tr>
                <th>No.</th>
                <th>Title</th>
                <th>Content</th>
                <th>Category</th>
                <th>Tags</th>
                <th width="200px">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->content }}</td>
                    <td>{{ $post->category->name }}</td>
                    <td>{{ collect($post->tags)->implode('name', ', ') }}</td>
                    <td>
                        <a href="{{route('web.post-detail', $post->slug)}}" class="btn btn-primary btn-sm">Detail</a>
                        <button wire:click.prevent="edit({{ $post->id }})" class="btn btn-primary btn-sm">Edit</button>
                        <button wire:click.prevent="confirmDelete({{ $post->id }})" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{-- $posts->links() --}}
    @endif

    @include('components.delete-confirm')
</div>
@push('scripts')
    <script>
        document.addEventListener("livewire:load", () => {
            let el = $('#tag_ids')
            initSelect()
            Livewire.hook('message.processed', (message, component) => {
                initSelect()
            })
            Livewire.on('setTagsSelect', values => {
                el.val(values).trigger('change.select2')
            })
            el.on('change', function (e) {
            @this.set('tag_ids', el.select2("val"))
            })
            function initSelect () {
                el.select2({
                    placeholder: '{{__('Select tag name')}}',
                    allowClear: !el.attr('required'),
                })
            }
        })
    </script>
@endpush
