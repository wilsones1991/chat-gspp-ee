import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function transformToUrl(fileName: string): string {
  // Check if the string starts with 'gspp.berkeley.edu'
  if (!fileName.startsWith('gspp.berkeley.edu')) {
    throw new Error('Invalid file name format');
  }

  // Replace underscores with slashes
  let url = fileName.replace(/_/g, '/');

  // Remove the .csv extension
  url = url.replace(/\.csv$/, '');

  return url;
}