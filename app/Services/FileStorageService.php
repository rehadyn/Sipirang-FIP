<?php

namespace App\Services;

use App\Models\BookingDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService
{
    /**
     * Store KTP file
     */
    public function storeKTP(UploadedFile $file): string
    {
        return $this->storeFile($file, 'uploads/ktp', 'KTP_' . Str::random(8));
    }

    /**
     * Store approval letter
     */
    public function storeApprovalLetter(UploadedFile $file): string
    {
        return $this->storeFile($file, 'uploads/approval-letters', 'SURAT_' . Str::random(8));
    }

    /**
     * Store room photo
     */
    public function storeRoomPhoto(UploadedFile $file): string
    {
        return $this->storeFile($file, 'public/room-photos', 'ROOM_' . Str::random(8));
    }

    /**
     * Store PDF (booking receipt or approval)
     */
    public function storePDF(string $content, string $filename): string
    {
        $directory = 'bookings/' . now()->format('Y/m');
        $path = $directory . '/' . $filename;
        
        Storage::disk('bookings')->put($path, $content);
        
        return $path;
    }

    /**
     * Get file URL for public files
     */
    public function getPublicUrl($path): ?string
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return null;
        }
        
        return route('storage.show', base64_encode($path));
    }

    /**
     * Get file as download response
     */
    public function getDownloadResponse($disk, $path, $filename)
    {
        if (!Storage::disk($disk)->exists($path)) {
            return null;
        }
        
        return Storage::disk($disk)->download($path, $filename);
    }

    /**
     * Delete file
     */
    public function deleteFile($disk, $path): bool
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        
        return true;
    }

    /**
     * Store file with validation
     */
    private function storeFile(UploadedFile $file, $directory, $prefix): string
    {
        $filename = $prefix . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk('uploads')->putFileAs($directory, $file, $filename);
        
        return $path;
    }

    /**
     * Get file info
     */
    public function getFileInfo($disk, $path): ?array
    {
        if (!Storage::disk($disk)->exists($path)) {
            return null;
        }
        
        return [
            'size' => Storage::disk($disk)->size($path),
            'mime' => Storage::disk($disk)->mimeType($path),
            'last_modified' => Storage::disk($disk)->lastModified($path),
        ];
    }

    /**
     * Store document and record in database
     */
    public function storeAndRecordDocument(
        UploadedFile $file,
        $bookingId,
        $documentType,
        $uploadedBy = 'borrower'
    ): BookingDocument {
        $disk = 'uploads';
        $storagePath = $this->storeFile($file, 'booking-documents', $documentType);
        
        return BookingDocument::create([
            'booking_id' => $bookingId,
            'document_type' => $documentType,
            'file_path' => $storagePath,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $uploadedBy,
        ]);
    }
}
