<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SimpleMessage;
use App\Models\SiteUser;
use Illuminate\Support\Facades\Auth;

class SimpleMessageController extends Controller
{
    /**
     * Mesaj gönder
     */
    public function send(Request $request)
    {
        try {
            $data = $request->validate([
                'recipient_id' => 'required|exists:site_users,id',
                'body' => 'required|string|max:2000',
            ]);
            $msg = preg_replace('/^.*?:\s*/', '', $data['body']);
            if (trim($msg) === '') {
                return redirect()->back()->with('swal', [
                    'title' => 'Mesajınız gönderilemedi',
                    'html' => 'Mesajınız gönderilemedi. Mesajınızı doğru bir formatta yazmış olduğunuza emin olun.',
                    'icon' => 'error',
                    'confirmButtonText' => 'Tamam',
                ]);
            } else {
                SimpleMessage::create([
                    'sender_id' => Auth::id(),
                    'recipient_id' => $data['recipient_id'],
                    'body' => $data['body'],
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('swal', [
                'title' => 'Mesajınız gönderilemedi',
                'html' => 'Mesajınız gönderilemedi. Hesabınıza <a href="/tr/giris-yap" style="text-decoration:underline;">giriş yap</a>mış olduğunuza ve mesajınızı doğru formatta yazdığınıza emin olun.',
                'icon' => 'error',
                'confirmButtonText' => 'Tamam',
            ]);
        }

        return redirect()->back()->with('swal', [
            'title' => 'Mesajınız iletildi',
            'html' => 'Mesajlarınızı ve cevaplarını <a href="/messages" style="text-decoration:underline;">mesajlar</a> sayfasından görebilirsiniz.',
            'icon' => 'success',
            'confirmButtonText' => 'Tamam',
        ]);


    }

    /**
     * İlan sahibi gözüyle: tüm farklı gönderenleri listele
     */
    public function threads()
    {
        $me = Auth::id();

        // 1) Size mesaj atan kullanıcılar
        $fromSenders = SimpleMessage::where('recipient_id', $me)
            ->pluck('sender_id');

        // 2) Siz mesaj gönderip bekledikleriniz
        $toRecipients = SimpleMessage::where('sender_id', $me)
            ->pluck('recipient_id');

        // 3) İkisini birleştir, benzersizleştir
        $participantIds = $fromSenders
            ->merge($toRecipients)
            ->unique()
            ->filter(fn($id) => $id !== $me)  // kendinizi çıkar
            ->values();

        $participants = SiteUser::whereIn('id', $participantIds)->get();

        return view('messages.threads', compact('participants'));
    }

    /**
     * Mevcut kullanıcı ($me) ile $user arasındaki konuşmayı göster
     */
    public function showThread(SiteUser $user)
    {
        $me = Auth::id();
        $other = $user->id;

        // 1) Mevcut konuşmayı getir (sizin ↔ diğer kişi)
        $conversation = SimpleMessage::where(function ($q) use ($me, $other) {
            $q->where('sender_id', $me)
                ->where('recipient_id', $other);
        })
            ->orWhere(function ($q) use ($me, $other) {
                $q->where('sender_id', $other)
                    ->where('recipient_id', $me);
            })
            ->orderBy('created_at')
            ->get();

        // 2) Kanal listesi için tüm ilgili kullanıcıları topla
        $fromSenders = SimpleMessage::where('recipient_id', $me)->pluck('sender_id');
        $toRecipients = SimpleMessage::where('sender_id', $me)->pluck('recipient_id');
        $participantIds = $fromSenders
            ->merge($toRecipients)
            ->unique()
            ->filter(fn($id) => $id !== $me)
            ->values();

        $participants = SiteUser::whereIn('id', $participantIds)->get();

        // 3) View’e hem conversation, hem user (diğer kişi), hem de participants dizisini gönder
        return view('messages.thread', compact('conversation', 'user', 'participants'));
    }

}
