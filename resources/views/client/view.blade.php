@extends('../_componets.main')
@section('main')
<div class="row justify-content-between mt-3">
            <div class="col-4">
                <h3 class="h1">
                    معلومات الزبون
                </h3>
            </div>
           <!--  <div class="col-3">
                <button class="btn btn-dark" type="button">العودة الى الصفحة الرئيسية</button>
            </div> -->
        </div>
        <main class="pt-3 py-3 my-3 mx-3">
            <div class="card border border-4" style="width: 18rem;">
                <div class="card-body">
                    <img src="{{asset('profile.png')}}" class="card-img-top border border-4" alt="picture">
                    <!-- Picture Free for personal and commercial use -->
                  <h6 class="card-subtitle mt-3 text-muted">الإسم الكامل</h6>
                  <h5 class="card-title mt-2">{{ $client->full_name}}</h5>
                  <h6 class="card-subtitle mb-2 text-muted">رقم الهاتف</h6>
                  <p class="card-text">
                  {{ $client->phone}}
                  </p>
                  <h6 class="card-subtitle mb-2 text-muted">العمليات</h6>
                  <div class="d-grid gap-2 d-md-block">
                  <form action="{{ route('clients.destroy',$client->id) }}" method="Post">
                  @csrf
                  @method('DELETE')
                  <a class="btn btn-secondary" href="{{ route('clients.edit',$client->id) }}">تعديل</a>
                  <button type="submit" class="btn btn-danger">حذف</button>
                  </form>
                  </div>
                  <h6 class="card-subtitle mb-2 mt-2 text-muted">التقارير - PDF</h6>
                  <a class="btn btn-secondary mt-2" href="{{ htmlspecialchars(route('pdf.info',$client->id).'#print') }}">بطاقة معلومات الزبون</a>
                  <a class="btn btn-success mt-2" href="{{ htmlspecialchars(route('pdf.payment',$client->id).'#print') }}">بطاقة الفاتورة</a>
                </div>
              </div>
              <div class="row justify-content-between mt-3">
                <div class="col-4">
                    <h3 class="h1">
                        المنتوجات
                    </h3>
                </div>
                <div class="col-2">
                    <a href="{{route('products.store-view',$client->id)}}" class="btn btn-dark">إضافة</a>
                </div>
              </div>
              <!-- table -->
                <table class="table table-striped border border-4">
                  <thead class="table-dark">
                  <tr>
                      <th scope="col">#</th>
                      <th scope="col">المنتوج</th>
                      <th scope="col">ثمنه</th>
                      <th scope="col">تم دفع</th>
                      <th scope="col">الباقي</th>
                      <th scope="col">تاريخ الإضافة</th>
                      <th scope="col">تاريخ أخر تعديل</th>
                      <th scope="col">العمليات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $i=0;
                   @endphp 
                    @foreach ($client->products as $products)
                    @php
                    $i++;
                   @endphp 
                  <tr>
                    @php
                    // get amout paid sum
                        $amoutSum = $products->payments->sum(function ($payment) {
                      return $payment->amount;
                      });
                    @endphp
                      <th scope="row">{{$i}}</th>
                      <td>{{$products->product_name }}</td>
                      <td>{{$products->product_prix }}</td>
                      <td>{{$amoutSum }}</td>
                      @if (($products->product_prix - $amoutSum) == 0)
                     <td class="bg-success h2">{{$products->product_prix - $amoutSum }}</td>
                     @else
                     <td class="bg-danger h2">{{$products->product_prix - $amoutSum }}</td>
                     @endif
                    <td>{{date('Y-m-d', strtotime($products->created_at))}}</td>
                      <td>{{date('Y-m-d', strtotime($products->updated_at))}}</td>
                      <td>
                        <div class="d-grid gap-2 d-md-block">
                          <a class="btn btn-success" href="{{ route('products.view',['id' => $products->id, 'client_id' => $client->id ]) }}">عرض</a>
                          </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
        </main>
@endsection
